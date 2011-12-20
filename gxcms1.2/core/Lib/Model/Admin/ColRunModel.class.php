<?php 
/**
 * @name    自定义采集执行模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class ColRunModel extends AdvModel {
    private $DB;
    private $ContDB;
    private $UrlDB;
    private $CModel;
    function __construct(){
		$this->DB     =D('co_node');
		$this->ContDB =D('co_content');
		$this->UrlDB  =D('co_urls');
		$this->CModel =D('Admin.CustomCollect');
		//Load('extend');
    }
    
    function GetParam(){
    	$Get=getReq($_REQUEST,array(
    	                'action'=>'string',
						'type'  =>'string',//采集类型 url/content
						'nid'   =>'int',   //节点id
    	                'page'  =>'int' ,
    	                'stime'  =>'int',   
					));
		if($Get['type']=='content'){
			$Get=array_merge($Get,getReq($_REQUEST,array( 'total' =>'int','pagesize'  =>'int','cmode'=>'int','action'=>'string')));//采集内容 每页采集数、间隔时间 
			if(empty($Get['pagesize']))  $Get['pagesize']=1;
			//if(empty($Get['stime']))     $Get['stime']=3;
		}
		//if($Get['action']=='real'){$Get['stime']=$_GET['stime'];}
         return $Get;
    }
    
    function Check($Get){
       if(empty($Get['nid'])) {$this->error='参数错误'; return false;}
       $where['id']=intval($_GET['nid']);
       $Data=$this->DB->where($where)->find();
       if (!$Data) {$this->error='该节点不存在或已删除'; return false;}
       
       if(!empty($Get['action'])){ 
      	$Data['action']=$Get['action'];
       }else {
      	$this->error='参数错误'; 
      	return false;
       }
       
       if( isset($Get['total']))    $Data['total'] = intval($Get['total']);
       if( isset($Get['page']))     $Data['page'] = intval($Get['page']);
       if( isset($Get['stime']))    $Data['stime']= intval($Get['stime']);
       if( isset($Get['pagesize'])) $Data['pagesize']= intval($Get['pagesize']);
       if( isset($Get['cmode']))    $Data['cmode']= intval($Get['cmode']);//采集模式
       return $Data;
    }
    
    
    /**
     * 采集影片Search
     */
    function ColVideoSearch(){
    	if(!empty($_REQUEST['nid']))   $Get['nid']  =trim($_REQUEST['nid']);
    	if(!empty($_REQUEST['title'])) $Get['title']=trim($_REQUEST['title']);
    	 return  $Get;           
    }
    
    /**
     * 采集影片入库list
     * @param $where
     */
    function ColVideoList($where){
    	if(!empty($where['title'])){
    		$title=$where['title'];
    		$where['title']=array('like','%'.$title.'%'); 
    	}
    	$order=empty($_GET['order'])?'addtime':$_GET['order'];
    	$sort =empty($_GET['sort'])?'desc':$_GET['sort'];
    	$order=$order.' '.$sort;
    	$where['status']=array('neq',0);
    	$video_count = $this->ContDB->where($where)->count('id');
		$video_page  = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$video_page  = get_cms_page_max($video_count,C('web_admin_pagenum'),$video_page);
		$video_url   = U('Admin-CustomCollect/ColVideo',array('nid'=>urlencode($where['nid']),'title'=>urlencode($title),'p'=>''),false,false);
		$pagelist   = get_cms_page($video_count,C('web_admin_pagenum'),$video_page,$video_url,'条记录');
		$_SESSION['video_reurl'] = $video_url.$video_page;	
	$Arr=$this->ContDB->field(C('db_prefix').'co_content.id,nid,name,status,url,data,addtime')->join(C('db_prefix').'co_node on '.C('db_prefix').'co_node.id=nid')
	      ->where($where)->order(C('db_prefix').'co_content.'.$order)->limit(C('web_admin_pagenum'))->page($video_page)->select();
	    //echo $this->ContDB->getLastSql();
	foreach($Arr as $key=>$val){
		if(!empty($val['data'])){ 
		 $val['data']=stripslashes($val['data']);	
		 $Arr[$key]['data']=collect::string2array($val['data']);
		 $Arr[$key]['base']=$this->ConvShow($Arr[$key]['data']);
		}
	}
	
	
	
	$ArrReturn=array('video'=>$Arr,'pagelist'=>$pagelist,'order'=>$order,'title'=>$title,'nid'=>$where['nid']);
	return 	$ArrReturn;
    	
    }
    
    /**
     * 采集网址list
     * @param array $Data  DB&Get
     * 
     */
    function CoUrl(&$Data){ 
       $ListUrls  =$this->GetList($Data);//获取列表页urls
       $TotalPage = count($ListUrls); 
			if ($TotalPage > 0) {
				$Page    = !empty($Data['page']) ? intval($Data['page']) : 0;
				$CurList = $ListUrls[$Page];//当前采集列表页url
				$ArtUrl  = $this->GetArtlist($CurList, $Data);//影片url	
				$ArtTotal= count($ArtUrl);
				$reNum =0;//重复记录个数
				$newNum=0;
			if (is_array($ArtUrl) && !empty($ArtUrl)) {
				foreach ($ArtUrl as $v) {//处理预定义及html标签
					//if (empty($v['url']) || empty($v['title'])) continue;
					if (empty($v['url'])) continue;
					$v = collect::Doaddslashes($v);
					$v['title'] = strip_tags($v['title']);
					if($Data['action']==='real'){//入库
						$where['md5'] = md5($v['url']);
						if (!$this->UrlDB->where($where)->find()) {//若不存在,更新入库
							$where['nid']=$Data['id'];
							$this->UrlDB->data($where)->add();
							$this->ContDB->data(array('nid'=>$where['nid'], 'status'=>0, 'url'=>$v['url'], 'title'=>$v['title'],'addtime'=>time()))->add();					
						    $newNum++;
						} else {//重复url 若ContDB中记录存在则更新status为0
							$cwhere['nid']=$Data['id'];
							$cwhere['url']=$v['url'];
							if($this->ContDB->where($cwhere)->find()){
							$this->ContDB->where($cwhere)->setField('status',0);
							}
							$reNum++;
						}
				   }
				  
				}
			    if($Data['action']==='real'){//入库
					if ($TotalPage <= $Page) {
						$this->DB->where('id='.$Data['id'])->save(array('lastdate'=>time()));
						//$this->error='采集完成！';		
					}
			    }
				$UrlArr=array(
				       'nid'     =>$Data['id'],
				       'name'    =>$Data['name'],
				       'url_list'=>$CurList,
				       'url'     =>$ArtUrl,
				       'page'    =>$Page+1,
				       'total_page'=>$TotalPage,
				       'total'   =>$ArtTotal,
				       'reNum'   =>$reNum,
				       'newNum'  =>$newNum,
				       'newAdd'  =>$ArtTotal-$reNum,
				       'action'  =>$Data['action'],
				       'stime'   =>$Data['stime']
				      );
				return $UrlArr;
			}else{ 
				   $this->error='采集失败，获取影片列表失败';
			       return false;
			   }
			} else {
				   $this->error='采集失败，没有可采集的网址';
				   return false;
			}
		}


    /**
     * collect demo
     * @param array $Url   第一个采集url array(title/url/[picurl]) 
     * @param array $Data  节点信息
     * @return array       采集结果
     */
     function Contest($Url,&$Data){//demo
     	$html=$this->GetCon($Url, $Data);
     	$Arr = $this->ConvShow($html);
		return $Arr;
     }
     
     /**
      * 转换显示采集内容结果
      * @param array $html
      */
     function ConvShow(&$html){
     	$BaseArr=$this->CModel->BaseArr();
		foreach($BaseArr as $k=>$v){  //选择不采集的字段将不在采集结果中显示
		   if(array_key_exists($k,$html)) $arr[$v]=$html[$k];
		}
     	return $Return=array('base'=>$arr,'playurl'=>$html['playurl']);
     }
     
     function GentHtml($id){
     	$html=$this->ContDB->where('id='.$id)->select();
     }

     
     function Collecting(&$config){
     	    $page  = !empty($config['page'])  ? intval($config['page']) : 1;
			$all   = !empty($config['total']) ? intval($config['total']) : 0;
			$where=array('nid'=>$config['id'], 'status'=>'0');
			//$where=array('nid'=>$config['id']);
			$total=$this->ContDB->where($where)->count();
			if(empty($all)) $all=$total;
			$total_page = ceil($all/$config['pagesize']);
			$maxpage=get_cms_page_max($total,$config['pagesize'],$page);
			$list=$this->ContDB->where($where)->order('id desc')->limit($config['pagesize'])->page($maxpage)->select();
             if($page<=1) $this->DB->where('id='.$config['id'])->save(array('lastdate'=>time()));
			//$i = 0;
			if (!empty($list) && is_array($list)) {
				
			   if ($total_page <$page) {
					//$this->DB->where('id='.$config['id'])->save(array('lastdate'=>time()));
					F('_gxcms/ColCache',NUll);
					$this->error='采集完成！';
					return true;
				}
				
				foreach ($list as $v) {			
					$html  =$this->GetCon($v, $config);
					$msg='<strong>'.$html['title'].'</strong> ';
					if(!empty($html['cname'])) {
						$cname=$this->CModel->GetCname($html['cname'],$v['nid']);
						if(!$cname){
							$msg.='分类<strong>'.$html['cname'].'</strong>待绑定';
							$html['cid']=999;
							//===========================================//
							//写入缓存
							$cache=F('_gxcms/channel_999');
							$cache[$v['id']]=array('id'=>$v['id'],'cname'=>$html['cname'].'_'.$v['nid']);
							F('_gxcms/channel_999',$cache);
						
						}else if($cname!=$html['cname']) {
							$msg.='<span>已自动将分类<strong>'.$html['cname'].'</strong>';
							$html['cname']=$cname;
							$msg.='转换为<strong>'.$html['cname'].'</strong></span>';
						}
					}
					$stauts=1;				
					if($config['direct'] && $html['cid']!=999){//直接入库video
						if(!$this->CModel->videoImport($v['url'],$html)){
						$msg.=$this->CModel->getError();
					    //continue;
						}else{
						$msg.='影片入库保存成功.';
						$stauts=2;
						}
					}
					$updata=array('status'=>$stauts, 'data'=>collect::array2string($html),'addtime'=>time());
					if(!empty($html['title']))  $updata['title']=$html['title'];
					$Update=$this->ContDB->where('id='.$v['id'])->save($updata);
					if($Update==='false'){//失败则跳过		
					$msg.=' 采集入库失败！';
					continue;
					}else{$msg.=' 采集成功.';}
					 	
					//$i++;
					if($config['stime']>0)  {//间隔时间
					sleep($config['stime']);
					$msg.='暂停'.$config['stime'].'秒继续采集...'; 
					}
					$result=array('con'=>$this->ConvShow($html),'url'=>$v['url'],'msg'=>$msg);
				  
				}
	            $StrUrl='?s=Admin/CustomCollect/ColRun/action/real/type/content/nid/'.$config['id'].'/page/'.($page+1).'/total/'.$all.'/stime/'.$config['stime'];
	            $result['StrUrl'] =$StrUrl;
	            $result['total']  =$all;
	            $result['already']=$page*$config['pagesize'];
	            $result['percent']=($result['already']/$result['total'])*200;
	            
			    if ($total_page >=$page){//添加缓存--续采
			    	$CacheUrl='?s=Admin/CustomCollect/ColRun/action/real/type/content/nid/'.$config['id'].'/page/'.$page.'/total/'.$all.'/stime/'.$config['stime'];
			    	F('_gxcms/ColCache',$CacheUrl);
			    } 
	            return $result;							
			}else {
				   if(F('_gxcms/ColCache')) F('_gxcms/ColCache',NUll);
					$this->error='没有要采集的影片';
					return false;
			}
            
     	
     }
     
     
		
    /**
	 * 获取影片网址
	 * @param string $url           采集地址
	 * @param array $config         配置
	 */
     function GetArtlist($url, &$config) {
		if ($html = collect::get_html($url, $config)) {
			if ($config['sourcetype'] == 4) { //RSS
				$html = xml::xml_unserialize($html);
				$data = array();
				if (is_array($html['rss']['channel']['item']))foreach ($html['rss']['channel']['item'] as $k=>$v) {
					$data[$k]['url'] = $v['link'];
					$data[$k]['title'] = $v['title'];
				}
			}else if ($config['sourcetype'] == 3) { //直接从内容页采集
				$data = array();
				$data[]=array('url'=>$url);
			} else {
				$html = collect::cut_html($html, $config['url_start'], $config['url_end']);
				$html = str_replace(array("\r", "\n"), '', $html);
				$html = str_replace(array("</a>", "</A>"), "</a>\n", $html);
				preg_match_all('/<a([^>]*)>([^\/a>].*)<\/a>/i', $html, $out);
			    $data = array();
			    
				/*=================================================*/
				//列表页获取图片
			   if($config['picmode']=='1'){
			  	 foreach ($out[2] as $k=>$v) {
			  		if ($config['picurl_rule']) {
							$ArrRule = collect::replace_sg($config['picurl_rule']);
						    foreach($ArrRule as $key=>$val) { $ArrRule[$key]=collect::str_replace_all($val);}
							$str = "/".$ArrRule[0]."([\s\S]*?)".$ArrRule[1]."/";
							if (preg_match($str, $v, $match_pic_out)) {
								$pic= collect::replace_item($match_pic_out[1],$config['picurl_filter']);
								$data[$k]['picurl'] = collect::url_check($pic, $url, $config);
							}			
			  		}
				  }
			    }
				/*======================================================*/
				
				//移除重复数据
				$out[1] = array_unique($out[1]);//url
				$out[2] = array_unique($out[2]);//title
				foreach ($out[1] as $k=>$v) {
					if (preg_match('/href=[\'"]?([^\'" ]*)[\'"]?/i', $v, $match_out)) {
						if ($config['url_contain']) {
							if (strpos($match_out[1], $config['url_contain']) === false) {
								continue;
							} 
						}
						if ($config['url_except']) {
							if (strpos($match_out[1], $config['url_except']) !== false) {
								continue;
							} 
						}
						$url2 = $match_out[1];
						$url2 = collect::url_check($url2, $url, $config);
						$data[$k]['url'] = $url2;
						$data[$k]['title'] = strip_tags($out[2][$k]);//去除标签
					}
				}
				if($config['colmode']=='desc')$data=get_collect_krsort($data);
				
			}
			return $data;
		} else {
			return false;
		}
	}
	



    /**
	 * 采集内容
	 * @param arr $source    采集地址 [url/title/picurl]
	 * @param array $config  配置参数
	 * @param integer $page  分页采集模式  暂无作用
	 */
	function GetCon($source, &$config, $page = 0) {
		set_time_limit(300);
		static $oldurl = array();
		$page = intval($page) ? intval($page) : 0;
		$url=$source['url'];
		 if($config['picmode']=='1'){
		    $data['picurl']=$source['picurl'];	
		 }
		if ($html = collect::get_html($url, $config)) {
			if (empty($page)) {
			    if(isset($config['fields'])) $config['fields']=collect::string2array($config['fields']);//字符串转换为数组
			   
				if(is_array($config['fields'])){
					foreach($config['fields'] as $k=>$v){
						//匹配规则,获取内容						
						if ($config[$v.'_rule']) {							
							$ArrRule = collect::replace_sg($config[$v.'_rule']);
							$data[$v]= collect::replace_item(collect::cut_html($html, $ArrRule[0], $ArrRule[1]), $config[$v.'_filter']);
						}
					}
				}


				if($config['menutype']=='1')  $data['cname'] = get_channel_name(intval($config['cid']));

				/*=================================================*/
				//播放地址
				if($config['range']=='1'){//播放列表范围
					 $plist=collect::cut_html($html, $config['playlist_start'], $config['playlist_end']);
				}else{$plist=&$html;}
				
				
				if($config['playmode']=='1') {//播放页获取单集播放url
					if($config['playlink_rule']) {
					 $out=$this->MatchAll($plist,$config['playlink_rule']);
				     foreach($out as $key=>$val){
				     $playlink[$key]= collect::replace_item($val, $config['playlink_filter']);
				     $playlink[$key]= collect::url_check($playlink[$key], $url, $config);
				     $phtml[$key] = collect::get_html($playlink[$key], $config);
				     if($config['purl_range']=='1'){
				     $phtml[$key]=collect::cut_html($phtml[$key], $config['playurl_start'], $config['playurl_end']);
				     }
				      //获取播放url 	
				     if ($config['playurl_rule']) {
				     	$ArrRule = collect::replace_sg($config['playurl_rule']);
					    $data['playurl'][$key]= collect::replace_item(collect::cut_html($phtml[$key], $ArrRule[0], $ArrRule[1]), $config['playurl_filter']);
				     }
				     }		  
					}
			  			
				}else{
						if( $config['playmode']=='2'){//播放页获取所有播放url
						     if ($config['playlink_rule']) {
							 $ArrRule = collect::replace_sg($config['playlink_rule']);
							 $playlink= collect::replace_item(collect::cut_html($plist, $ArrRule[0], $ArrRule[1]), $config['playlink_filter']);
							 }
							 $playlink = collect::url_check($playlink, $url, $config);
							 $phtml = collect::get_html($playlink, $config);
							 if($config['purl_range']=='1'){
				     	     $phtml=collect::cut_html($phtml, $config['playurl_start'], $config['playurl_end']);
				             }
						}else{
						       $phtml=&$plist;//默认内容页获取所有播放url
						}
						
						if ($config['playurl_rule']) {//playurl 单集播放地址
					    	 $out=$this->MatchAll($phtml,$config['playurl_rule']);
						     foreach($out as $key=>$val){
						     $data['playurl'][]= collect::replace_item($val, $config['playurl_filter']);
						     }
						}
					
					
				}
                /*================================*/
				//获取分集名称
				if($config['vnamemode']==2){
				    if ($config['vname_rule']) {	
				    	 $vout=$this->MatchAll($phtml,$config['vname_rule']);
				         foreach($vout as $key=>$val){
						 $data['vname'][]= collect::replace_item($val, $config['vname_filter']);
						 }	
						 foreach($data['playurl'] as $k=>$v){
						 $data['playurl'][$k]=$data['vname'][$k].'$'.$v;
						 }			
					}
				}
		
				/*=================================================*/
				//图片处理
				$data['picurl']=preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/ie', "'$1'", $data['picurl']);
				if($config['action']=='real'){
					if (empty($page) && !empty($data['picurl']) && C('upload_http') == 1) { //下载图片
						$Down = D('Down');
						$data['picurl']=$Down->down_img($data['picurl']);
					}
					//采集时间
					if(empty($data['time']))  $data['time'] = time();
				}
				
			}
			/*if ($page == 0) {
				$data['content'] = preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/ie', "collect::download_img('$0', '$1')", $data['content']);
			}*/
			return $data;
		}
	}
	
	
	    /**
		 * 得到需要采集的网页列表页
		 * @param array $config 配置参数
		 * @param integer $num  返回数
		 */
		 function GetList(&$config, $num = '') {
			$url = array();
			switch ($config['sourcetype']) {
				case '1'://序列化
					$num = empty($num) ? $config['pagesize_end'] : $num;
					if($config['pagesize_start']<=0) $config['pagesize_start']=1;
					for ($i = $config['pagesize_start']; $i <= $num; $i = $i + $config['par_num']) {
						$url[$i-1] = str_replace('(*)', $i, $config['urlpage']);
					}
					if($config['colmode']=='desc') $url=get_collect_krsort($url);
					break;
				case '2'://多网址
					$url = explode("\r\n", $config['urlpage']);
					if($config['colmode']=='desc') $url=get_collect_krsort($url);
					break;
				case '3'://单一网址
				case '4'://RSS
					$url[] = $config['urlpage'];
					break;
			}
			return $url;
		}
		
		/**
		 * 匹配获取所有
		 * @param string config_rule
		 * @return Array
		 */
		function MatchAll(&$html,$rule){
			$ArrRule = collect::replace_sg($rule);
			foreach($ArrRule as $key=>$val){
			$ArrRule[$key]=collect::str_replace_all($val);
			}
			$str = "/".$ArrRule[0]."([\s\S]*?)".$ArrRule[1]."/";
			preg_match_all($str, $html, $out);
			if($out) return $out[1];
			return false;
		}
		
		
		function GetPlayUrl(&$html,$rule,$filter){
			$out=$this->MatchAll($html,$rule);
			foreach($out as $key=>$val){
			$data['playurl'][]= collect::replace_item($val, $filter);
			}
			return $data['playurl'];   
		}
		

		
		/**
		 * 采集影片入库管理
		 * @param $act
		 */
		function Inflow($act){
			if($act=='inflow'){
				if(empty($_POST['ids'])) {
					$this->error='请选择需入库影片!';
					return false;
				}
				$ArrID=$_POST['ids'];
			}else {
				$where['status']=array('neq',0);
				if($act=='today')     $where['addtime']=array('gt',xtime(1));
				if($act=='allunused') $where['status']=1;
				if($act=='allinflow') $where='';
				
				$All=$this->ContDB->field('id')->where($where)->select();
				foreach($All as $k=>$v){
				$ArrID[$k]=$v['id'];
				}
				
			}
			foreach($ArrID as $key=>$val){
				$Cont=$this->ContDB->field('nid,url,data')->where('id='.$val)->find();
				$data=collect::string2array(stripslashes($Cont['data']));
				$result.='['.$data['cname'].']<strong>'.$data['title'].'</strong>';
				if(!$this->CModel->videoImport($Cont['url'],$data,$Cont['nid'])){
			    $result.=$this->CModel->getError()."\n\r";
			     continue;
				}else{
				$UpCont=array('status'=>2);
				/*if($data['cid']==999){
					unset($data['cid']);
					$UpCont['data']=collect::array2string($data);
				}*/	
				$Update=$this->ContDB->where('id='.$val)->save($UpCont);
				$result.="入库成功!\n\r";	
				}	
			}
			$result=explode("\n\r",$result);
			return $result;
			//if(!empty($this->error)) return false;
			//return true;
		}
			
		 /**
		 * 采集影片删除
		 * @param $act
		 * 是否删除源链接库数据？
		 */	
	    function Del($act){
	  	 if($act=='delselect' || $act=='delonly'){//delonly 不希望再次采集，不删除url
	       if(!empty($_POST['ids'])) {
	       	$where['id']=array('in',$_POST['ids']);
	       /****************************/
	       	//删除历史url表记录
	       	if($act=='delselect'){
	       	  $urls=$this->ContDB->field('url')->where($where)->select();
	       	  foreach($urls as $k=>$v){$Arrmd5[]=md5($v['url']);}
	       	  $Wmd5['md5']=array('in',$Arrmd5);	
	       	  if(!$this->UrlDB->where($Wmd5)->delete()) {
	       	  	 echo $this->UrlDB->getDBError();
	       	    $this->error=$this->UrlDB->getDBError();
	       	  	return false;
	       	  }
	       	}
	       	$this->ContDB->where($where)->delete();
	       	return true;
	       }else{
	       	$this->error='请选择要删除的影片';
	       	return false;
	       }
	  	 }else if($act=='delall'){
	  	   $this->ContDB->delete();
	  	   return true;	
	  	 }else if($act=='del'){
	  	   $this->ContDB->where('id='.$_GET['vid'])->delete();
	  	   return true;	
	  	 }
	       return false;
		}
		
	
}
?>