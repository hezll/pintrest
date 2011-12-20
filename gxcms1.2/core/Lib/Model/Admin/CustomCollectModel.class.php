<?php 
/**
 * @name    自定义采集模块管理相关
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class CustomCollectModel extends Model {
    private $DB;
    private $CDB;
    private $VdoDB;
    private $ContDB;
    function __construct(){
		$this->DB   =D('co_node');
		$this->CDB  =D('co_channel');
		$this->VdoDB=D('video');
		$this->ContDB=D('co_content');
		
    }
	function GetParam(){
	      	$data = $_POST['data']; 
	      	$data['__hash__'] =$_POST['__hash__'];
		    $data['urlpage']  =$_POST['urlpage'.$data['sourcetype']] ;//采集地址
		    if($data['picmode']=='1') {$data['picurl_rule']=$data['picurl1_rule'];$data['picurl_filter']=$data['picurl1_filter'];}
		    if(empty($data['direct'])) $data['direct']=0;
		    if(empty($data['colmode'])) $data['colmode']='';
          return $data;
	}
	function CheckParam($data){
		    $data['name']=trim($data['name']);
	    	if (empty($data['name']))             {$this->error='采集节点项目名称为空！'; return false;}
	    	if(!get_channel_son($data['cid']))     {$this->error='请选择当前分类下面的子类栏目！';  return false;}
			if($data['menutype']=='1' && $data['cid']=='0') {$this->error='请选择栏目分类！';   return false;}//检测栏目分类
		
			return true;
	}
	
	function Save($data){//入库
			if(is_array($data['fields'])) $data['fields'] =collect::array2string($data['fields']);//数组转换为字符串
			if(empty($data['nid']))  $data['lastdate']=time();
			if($this->DB->create($data)){	
				if(!empty($data['nid'])){
				$id = $this->DB->where('id='.$data['nid'])->data($data)->save();
				$LastID=$data['nid'];
				}else{	
				$id = $this->DB->add($data);
				$LastID=$id;
				}
				if($id==='false') return false;
				$this->ColMemcache();
				return $LastID;
				//return true;
			}else{
				//echo  $this->DB->getDbError();
				return false;
			}			   
	}
	
	function ActionCheck(){
		if(isset($_GET['nid']) ) {$nid = intval($_GET['nid']);} else{return false;}
		if($ArrSour=$this->NodeData($nid)) return $ArrSour;
		return false;
	}
	
	function Copy($data){
		$data['name']=$_POST['name'];
		if(empty($data['name'])){$this->error='请输入新采集项目名称';return false;}
		if($data){
			$data['__hash__'] =$_POST['__hash__'];
			unset($data['id']);	
			$Result = $this->Save($data);
			if($Result){$this->ColMemcache();  return true;}
			return false;
		}
	} 
	
	function ExpCode(){
		$Data=$this->ActionCheck();
		if($Data) return 'BASE64:'.base64_encode(json_encode($Data)).':END';
		return false;
	}
	
    function SaveImport(){
    	if($_POST['importmode']=='txt'){//导入txt格式
    	$filename = $_FILES['file']['tmp_name'];
		if (strtolower(substr($_FILES['file']['name'], -3, 3)) != 'txt') {
		$this->error='只支持导入txt格式文件';	
	    return false;
		}
		$StrData=@file_get_contents($filename);	
		@unlink($filename);	   	 	
	    }else{
    	$StrData=$_POST['txtcode'];
	    }
	    
	     //入库
    	if($StrData){	
	    	$Arr    =explode(':',$StrData);
	    	$ArrData=json_decode(base64_decode($Arr[1]),true);
	    	if(!$ArrData) return false;
	    	$ArrData['__hash__'] =$_POST['__hash__'];
	    	unset($ArrData['id'],$ArrData['lastdate']);
	        $Result =$this->Save($ArrData);
	        $this->ColMemcache(); 
            return $Result;
    	}else{
    		return false;
    	}
    }
    
	function Del(){
       if(!empty($_GET['nid'])) {
       	$where['id']=$_GET['nid'];
       	$this->DB->where($where)->delete();
       	$this->ColMemcache();
       	return true;
       }
       return false;
	}
	
    function DelAll(){
       if(!empty($_POST['ids'])) {
       	$where['id']=array('in',$_POST['ids']);
       	$this->DB->where($where)->delete();
       	$this->ColMemcache();
       	return true;
       }
       return false;
	}
	
	//预定义的字符转换为 HTML 实体,防止显示混乱如<!-- 
	function DataShow($nid){
		$Data=$this->NodeData($nid);
		foreach($Data as $key=>$val){
		if($key!='fields') $Data[$key]=htmlspecialchars($val);
	   }		
	   return $Data;
	}
	
	function NodeData($nid){//获取某 nid数据
	   $NodeData=$this->DB->where('id='.$nid)->find();
	   foreach($NodeData as $key=>$val){
	   	    $NodeData[$key]=stripslashes($val);//反转义字符
	   }
	   if(isset($NodeData['fields'])) $NodeData['fields']=collect::string2array($NodeData['fields']);//字符串转换为数组
	   return $NodeData;
	}
	
	//分页显示自定义采集节点
	function ShowList(){	
	    $node_count = $this->DB->where($where)->count('id');
		$node_page  = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$node_page  = get_cms_page_max($node_count,C('web_admin_pagenum'),$node_page);
		$node_url   = U('Admin-CustomCollect/ListShow',array('keyword'=>urlencode($keyword),'p'=>''),false,false);
		$pagelist   = get_cms_page($node_count,C('web_admin_pagenum'),$node_page,$node_url,'条记录');
		$_SESSION['node_reurl'] = $node_url.$node_page;	
	$Node   =$this->DB->field('id,name,sourcetype,urlpage,lastdate')->order('id desc')->limit(C('web_admin_pagenum'))->page($node_page)->select();
	foreach($Node as $key=>$val){
		if($val['sourcetype']=='2'){
			$Node[$key]['urlpage']=str_replace("\r\n", "<br/>", $val['urlpage']);		
		}
	}

	$ArrNode=array('node'=>$Node,'pagelist'=>$pagelist);
	return 	$ArrNode;
	}
	
	//test url
	function GetUrl(){
	    $sourcetype     = isset($_GET['sourcetype']) && intval($_GET['sourcetype']) ? intval($_GET['sourcetype']) : $this->error='参数错误';
		$pagesize_start = isset($_GET['pagesize_start']) && intval($_GET['pagesize_start']) ? intval($_GET['pagesize_start']) : 1;
		$pagesize_end   = isset($_GET['pagesize_end']) && intval($_GET['pagesize_end']) ? intval($_GET['pagesize_end']) : 1;
		$par_num = isset($_GET['par_num']) && intval($_GET['par_num']) ? intval($_GET['par_num']) : 1;
		$urlpage = isset($_GET['urlpage']) && trim($_GET['urlpage']) ? str_replace('@','/',trim($_GET['urlpage'])) : $this->error=$_GET['urlpage'].'采集失败';
        while ($pagesize_start <= $pagesize_end){
	       $ArrUrl[]=str_replace('(*)', $pagesize_start, $urlpage);
	       $pagesize_start=$pagesize_start+$par_num;
        }
        if(!empty($this->error)) return false;
        return $ArrUrl;
	}
	
	function BaseArr(){
		$data=array(
			'title'    =>'影片名称',
		    'cname'    =>'栏目分类',
			'intro'    =>'备注',
		    'time'     =>'更新时间',
			'director' =>'导演',
			'actor'    =>'主演',
			'content'  =>'剧情介绍',
			'picurl'   =>'图片',
			'area'     =>'地区分类',
			'language' =>'语言/对白',
			'year'     =>'上映时间',
			'serial'   =>'连载信息',
			);
	return $data;
	}
	
	function FilterRules(){
		$rule=array(
		'&lt;p&gt;'     =>'&lt;p([^&gt;]*)&gt;(.*)&lt;/p&gt;[|]',
		'&lt;a&gt;'     =>'&lt;a([^&gt;]*)&gt;(.*)&lt;/a&gt;[|]',
		'&lt;script&gt;'=>'&lt;script([^&gt;]*)&gt;(.*)&lt;/script&gt;[|]',
		'&lt;iframe&gt' =>'&lt;iframe([^&gt;]*)&gt;(.*)&lt;/iframe&gt;[|]',
		'&lt;table&gt;' =>'&lt;table([^&gt;]*)&gt;(.*)&lt;/table&gt;[|]',
		'&lt;span&gt;'  =>'&lt;span([^&gt;]*)&gt;(.*)&lt;/span&gt;[|]',
		'&lt;b&gt;'     =>'&lt;b([^&gt;]*)&gt;(.*)&lt;/b&gt;[|]',
		'&lt;img&gt;'   =>'&lt;img([^&gt;]*)&gt;[|]',
		'&lt;object&gt;'=>'&lt;object([^&gt;]*)&gt;(.*)&lt;/object&gt;[|]',
		'&lt;embed&gt;' =>'&lt;embed([^&gt;]*)&gt;(.*)&lt;/embed&gt;[|]',
		'&lt;param&gt;' =>'&lt;param([^&gt;]*)&gt;(.*)&lt;/param&gt;[|]',
		'&lt;div&gt;'   =>'&lt;div([^&gt;]*)&gt;[|]',
		'&lt;/div&gt;'  =>'&lt;/div&gt;[|]',
		'&lt;!-- --&gt;'=>'&lt;!--([^&gt;]*)--&gt;[|]'
		);
		/*$html_tag = array(
		"<p([^>]*)>(.*)</p>[|]"           =>'<p>', 
		"<a([^>]*)>(.*)</a>[|]"           =>'<a>',
		"<script([^>]*)>(.*)</script>[|]" =>'<script>',
		 "<iframe([^>]*)>(.*)</iframe>[|]"=>'<iframe>',
		 "<table([^>]*)>(.*)</table>[|]"  =>'<table>', 
		 "<span([^>]*)>(.*)</span>[|]"    =>'<span>',
		 "<b([^>]*)>(.*)</b>[|]"          =>'<b>', 
		 "<img([^>]*)>[|]"                =>'<img>', 
		 "<object([^>]*)>(.*)</object>[|]"=>'<object>', 
		 "<embed([^>]*)>(.*)</embed>[|]"  =>'<embed>', 
		 "<param([^>]*)>(.*)</param>[|]"  =>'<param>', 
		 "<div([^>]*)>[|]"                =>'<div>', 
		 "</div>[|]"                      =>'</div>',
		 "<!--([^>]*)-->[|]"              =>'<!-- -->');*/
	
		return $rule;
	}
	
	// 生成采集节点项目缓存
     function ColMemcache(){
		$list = $this->DB->field('id,name')->order('id asc')->select();
		F('_gxcms/ColNode',$list);
    }
	
    
    // 自定义分类缓存
     function ChanlMemcache(){
		$list = $this->CDB->order('id asc')->select();
		foreach($list as $k=>$v){
			$list[$k]['cname']=$v['cname'].'_'.$v['nid'];
			unset($list[$k]['nid']);
		}
		F('_gxcms/Autochannel',$list);
		
    }
    
    /**
     * 更新自定义分类转换时 更新对应table col_content
     * @param $cname
     */
    function ColChannelFolow($cname,$nid,$reid){
    	$find=collect::get_channel999_id($cname,$nid);
    	$ArrF=F('_gxcms/channel_999'); 
    	if($find){
    	 //update table col_content
    	 foreach($find as $k=>$v){
    	 $Cont=$this->ContDB->field('data')->where('id='.$v['id'])->find();
    	 $rename=get_channel_name($reid);
    	 $Out=str_replace(array("\'cid\' => \'999\',","\'".$cname."\'"),array('',"\'".$rename."\'"),$Cont['data']);
    	 $Update=$this->ContDB->where('id='.$v['id'])->save(array('data'=>$Out));
    	 if($Update==='false') return false;
    	 unset($ArrF[$v['id']]);
    	 }
        F('_gxcms/channel_999',$ArrF);
        return true;
    	}else{
    		return false;
    	}
    }
   
	
	//自定义分类管理
	function ChannelManage($act){
		$Get=getReq($_REQUEST,array('cname'=>'string','reid'=>'int','nid'=>'int'));
		foreach($Get as $k=>$v){ $Get[$k]=trim($v);}
		$Get['cname']=urldecode($Get['cname']);
		$where['id']=$_GET['id'];
	    if(!empty($Get['reid']) && !get_channel_son($Get['reid'])) {$this->error='请选择当前分类下面的子类栏目！';  return false;}
	    if($act=='add' || $act=='update') {
			if(empty($Get['cname'])) {$this->error='要转换的 栏目 为空'; return false;}
		    if(empty($Get['reid']))  {$this->error='请选择对应的系统栏目'; return false;} 
		    //检测重复性
		    $id=$this->CDB->field('id')->where($Get)->find();
		 
		    if($id && $id['id']!=$_GET['id']){$this->error='重复设置'; return false;}
		}
	    switch($act){
	  	case 'add':
	  	    $id = $this->CDB->add($Get);
	  	    if(!id) return false;
	  		break;
	  	case 'update':
	  		$id = $this->CDB->where($where)->data($Get)->save(); 
	  	    if(!id) return false; 
	  	    break;
	  	case 'del':$this->CDB->where($where)->delete(); break;
	  	default:  
	  	    break;
	   }
	   $this->ChanlMemcache();
	   if($act=='add' || $act=='update') {
	   $up=$this->ColChannelFolow($Get['cname'],$Get['nid'],$Get['reid']);//更新table col_content
	   if($up) return true;
	   } 
	  return true;
	}
	
	/**
	 * 自定义栏目分类转换 接收查询参数
	 */
	function ChannelSearch(){
		if(!empty($_POST['search'])){
		$Get=getReq($_REQUEST,array(
    	                'cname'=>'string',
						'reid'  =>'int',
						'nid'   =>'int'
					));
		foreach($Get as $k=>$v){
			if(!empty($v)) $Param[$k]=trim($v);
		}
		return 	$Param;		
		}
	}
	
   //分页显示自定义栏目分类转换
	function ChannelList($where){	
	    $count = $this->CDB->where($where)->count('id');
		$page  = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$page  = get_cms_page_max($count,C('web_admin_pagenum'),$page);
		$url   = U('Admin-CustomCollect/AutoChannel',array('cname'=>$where['cname'],'reid'=>$where['reid'],'nid'=>$where['nid'],'p'=>''),false,false);
		$pagelist   = get_cms_page($count,C('web_admin_pagenum'),$page,$url,'条记录');
		$_SESSION['reurl'] = $url.$page;	
	    $Arr =$this->CDB->where($where)->order('id desc')->limit(C('web_admin_pagenum'))->page($page)->select();
	    $CacheData= F('_gxcms/channel_999');
	    if(!empty($CacheData)){
	    	foreach($CacheData as $key=>$val){
	    	$Cache[] = explode('_',$val['cname']);
	    	}
	    	$Cache=array_unique($Cache);
	    	foreach($Cache as $k=>$v){
	    		$Cache[$k][1]=collect::get_node_name($v[1]);
	    	}
	    }
	    $Result=array('arr'=>$Arr,'cache'=>$Cache,'pagelist'=>$pagelist,'search'=>$where);
	return 	$Result;
	}
	
	/**
	 * //采集获取的栏目分类绑定
	 * @param string $getname
	 * @param mixed  $nid
	 * @return string $cname 系统栏目名称
	 */
	function GetCname($getname,$nid='0'){
		$cname=collect::get_syschannel_id($getname,'cname');//默认自动匹配系统分类
		if(!$cname){
				$cid=collect::get_Autochannel_id($getname,$nid);//先检测是否有对应自定义分类
				if($cid){ 
				$cname=get_channel_name($cid);
				}else{
				$cname=collect::get_channel_id($getname,'cname');//自动智能检测
				}
		}
		return $cname;
	}	
	
	
	
	
	
	/**
	 * 直接入库 array $html(cid)+string content.url   
	 * 先检测 ——>组合数据——>添加/更新 
	 * @param string $url
	 * @param array  $html
	 */
     function videoImport($url,$html,$nid=''){
     	foreach($html['playurl'] as $v) {$playurl.=$v."\r\n";}
	    $html['playurl']=trim($playurl);
     	$check=$this->UpCheck($url,$html,$nid);
     	if(!$check){
     		$this->error=$this->getError();
     		return false;
     	}else{//组合数据 
	      if(!empty($html['cname']) && (empty($html['cid'])|| $html['cid']==999)) {
	      	if($html['cid']==999) {//采集时未匹配到系统分类的
		      	$cid=collect::get_Autochannel_id($html['cname'],$nid);//再次检测是否有对应自定义分类
				if($cid) $html['cid']=$cid;
	      	}else{
                $html['cid']=collect::get_syschannel_id($html['cname']);//cname 转换为系统栏目id 
	      	}
	      	
     	    if(!$html['cid'] || $html['cid']==999) {
     	   	$this->error='没找到对应栏目，入库失败！';
     	   	return false;
     	    }
     	    unset($html['cname']);
	      }
	      $html['reurl']=$url;
	      
	      if($check!='add' && $check!='status') {//返回id更新数据
	      	 return $this->VideoSave($html,$nid,$check);
	      }else{//新增
	      	 if($check==='status') $html['status']=-1; 
	      	 return $this->VideoSave($html,$nid);
	      }
     	}  	
     }
     
     
     /**
      * 入库保存
      * @param array $html
      * @param int   $id
      */
     function VideoSave(&$html,$nid,$id=''){
     	C('TOKEN_ON',false);//关闭令牌验证
     	if(!empty($id)){//update
          $data['cid']     = $html['cid'];
		  $data['serial']  = $html['serial'];
		  $data['playurl'] = $html['playurl'];
		  $data['addtime'] = time();
		  $data['reurl']   = $html['reurl']; 
		  if($this->VdoDB->create($data)) {
		  $sid = $this->VdoDB->where('id='.$id)->data($data)->save();	
		  if($sid)return true;
		  $this->error=$this->VdoDB->getDBError();
		  return false;	
		  }else{
		  $this->error=$this->VdoDB->getError();
		  return false;
		  }  
     	}else{//add
     	  $html['addtime'] = time();
		  $html['stars']   = 1;
		  $html['letter']  = get_letter($html['title']);
		  $html['hits']    = mt_rand(0,C('web_admin_hits'));
		  $html['score']   = mt_rand(1,C('web_admin_score'));
		  $html['scoreer'] = mt_rand(1,C('web_admin_score'));
		  $html['up']      = mt_rand(1,C('web_admin_updown'));
		  $html['down']    = mt_rand(1,C('web_admin_updown'));
		  $html['inputer'] = 'custom_'.$nid;   
     	  if($this->VdoDB->create($html)) {
		  $sid = $this->VdoDB->add($html);	
		  if($sid) return true;
		  $this->error=$this->VdoDB->getDBError();
		  return false;	
		  }else{
		  $this->error=$this->VdoDB->getError();
		  return false;
		  }
     	} 	
     }
     
     
     /**
      * 检测
      * @param $url
      * @param $html
      * @return false 不更新; add 新增;id 更新;status 相似
      */
     function UpCheck($url,&$html,$nid){
     	 if(empty($html['title']) || empty($html['playurl'])) {$this->error='影片名称或播放地址为空!';return false;}

     	 //过滤常规重复字符
		 $html['title']    = str_replace(array('HD','BD','DVD','VCD','TS','【完结】','【】','[]','()'),'',$html['title']);
		 $html['actor']    = str_replace(array(',','/','，','|','、'),' ',$html['actor']);
		 $html['director'] = str_replace(array(',','/','，','|','、'),' ',$html['director']);
		
     	 //检测video库中是否已含有该源链接的影片 	  
	     $ArrUrl=$this->VdoDB->field('id,title,playurl,inputer,reurl')->where("reurl='".$url."'")->find();    
	     if(!$ArrUrl){//若没有相同源链接影片,则检测影片名称
	        $ArrTitle=$this->VdoDB->field('id,title,actor,playurl,inputer,reurl')->where("title='".$html['title']."'")->find();
	        if($ArrTitle){ 
	          if($this->InfoCheck($html,$ArrTitle)){
		      if($this->BaseCheck($ArrTitle,$html)) return $ArrTitle['id'];
		      return false;
		      }
	        }
	        
	      //无来源，无同名，不是同一个采集项目时，检测相似    
		    if(C('web_collect_num')){
		    $len = ceil(strlen($html['title'])/3)-intval(C('web_collect_num'));
			if($len >= 2){
			$like = get_replace_html($html['title'],0,$len);
			$where['title'] = array('like','%'.$like.'%');
			$ArrLike = $this->VdoDB->field('id,title,inputer,playurl,actor')->where($where)->find();
			
			//主演完全相同则更新
			if(!empty($html['actor']) && !empty($ArrLike['actor']) ){
			//对比
			$arr_actor_1 = explode(' ',$html['actor']);
			$arr_actor_2 = explode(' ',str_replace(array(',','/','，','|','、'),' ',$ArrLike['actor']));
			if(!array_diff($arr_actor_1,$arr_actor_2) && !array_diff($arr_actor_2,$arr_actor_1)){//若差集为空
			if($this->BaseCheck($ArrLike,$html)) return $ArrLike['id'];
			}
			//主演不完全相同则添加
			}	
					
			if($ArrLike && $ArrLike['inputer']!='custom_'.$nid)  return 'status';	
			return 'add';				
			}
			}     	
            return 'add';    
	     }else{
	        if($this->BaseCheck($ArrUrl,$html))  return $ArrUrl['id'];
	        return false;
	     }
	     
     }
     
    /**
     * 附加信息检测
     * @param $html 采集内容
     * @param $Arr  数据库info
     * @return true 检测base更新;false add
     */ 
     function InfoCheck(&$html,&$Arr){
         if(empty($html['actor']))             return true;  //无主演时直接更新该影片
		 if($Arr['actor'] == $html['actor'])   return true;  //演员完全相等时更新该影片
				
		 //有相同演员时更新该影片
		 $arr_actor_1 = explode(' ',$html['actor']);
		 $arr_actor_2 = explode(' ',str_replace(array(',','/','，','|','、'),' ',$Arr['actor']));
		 if(array_intersect($arr_actor_1,$arr_actor_2)) return true; 
		 return false;   	
     }
  
 
    /**
     * 检测是否有更新
     * @param $data
     * @param $html
     * @return boolen false 不需要更新 ; true 更新
     */
	function BaseCheck($data,&$html){
		if('gxcms' == $data['inputer']){
			$this->error='站长手动锁定，不需要更新!';
			return false;
		}
		if ($data['playurl'] == $html['playurl']) {
			$this->error='播放地址未变化，不需要更新!';
			return false;
		}
	    $old_line  = count(explode(chr(13),$data['playurl']));
		$new_line  = count(explode(chr(13),trim($html['playurl'])));
		if($new_line < $old_line){
		    $this->error='小于数据库集数，不需要更新!';
		    return false;
		}
		/*if (mb_strlen($data['playurl'],'utf-8') > mb_strlen($html['playurl'],'utf-8')) {
			$this->error='当前播放地址较陈旧，不需要更新!';
			return false;
		}*/	
		return true;	
	}
     

	
	
}
?>