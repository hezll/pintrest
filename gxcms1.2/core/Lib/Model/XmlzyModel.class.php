<?php
/**
 * @name    一键采集模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class XmlzyModel extends Model {	
	
    private $VdoDB;
    function __construct(){
		$this->VdoDB=D('video');	
    }
    
	
	/**
	 * 通过远程地址参数抓取需要的数据
	 * GetParams
	 */
    public function xml_httpurl(){
		$array_url = array(); //本地程序跳转参数与远程变量参数
		$array_tpl = array(); //本地程序模板变量参数
		
		//获取跳转参数
		$array_url['ziyuan'] = !empty($_GET['ziyuan'])?trim($_GET['ziyuan']):'gxcms'; //合作资源站程序
		$array_url['fid']    = $_GET['fid'];    //合作渠道ID
		$array_url['action'] = $_GET['action']; //是否入库(all/day/ids)
		$array_url['xmlurl'] = $_GET['xmlurl']; //资源库网址
		$array_url['reurl']  = $_GET['reurl'];  //来源网址
		$array_url['pic']    = $_GET['pic'];    //重采图片
		//
		$array_url['cid']    = $_GET['cid']; //指定视频分类
		$array_url['wd']     = $_GET['wd'];
		
		if($_POST['wd']){//指定关键字
		$array_url['wd'] = trim($_POST['wd']);
		} 
		
		$array_url['h']      = intval($_GET['h']); //指定时间
		$array_url['p']      = !empty($_GET['p'])?intval($_GET['p']):1; $array_url['page'] = $array_url['p']; //指定分页	
		$array_url['vodids'] = $_GET['vodids']; //指定视频ID
		
		if($_POST['ids'] && ($array_url['action']=='ids')){//手工选择要采集影片
		$array_url['vodids'] = implode(',',$_POST['ids']);
		}			

		$array_url['inputer'] = $_GET['inputer']; //指定资源库频道
		$array_url['play']    = $_GET['play']; //指定播放器组(如不指定则为目标站的全部播放器组)
		// 分支资源库程序
		$zymodel = 'ziyuan_'.$array_url['ziyuan'];
		return $this->$zymodel($array_url);
	}
	
	
	
	/**
	 * 资源站为A类型 
	 * @param  array $array_url
	 * @return array
	 */
    public function ziyuan_a($array_url){
		//组合资源库A类型的URL地址并获取XML资源
		$array_tpl['httpurl']     = '&wd='.urlencode($array_url['wd']).'&t='.$array_url['cid'].'&h='.$array_url['h'].'&ids='.$array_url['vodids'].'&pg='.$array_url['p'];
		if($array_url['action']){
			$array_tpl['httpurl'] = str_replace('?ac=list','?ac=videolist',$array_url['xmlurl']).$array_tpl['httpurl'];
		}else{
			$array_tpl['httpurl'] = $array_url['xmlurl'].$array_tpl['httpurl'];
		}
		$array_tpl['httpurl']     = str_replace('@','/',$array_tpl['httpurl']);//还原目标网址
		//抓取地址开始
		$xml = get_collect_file($array_tpl['httpurl']);
		if ($xml) {
			//组合分页信息
			preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$xml,$page_array);
			$xml_page['recordcount'] = $page_array[4];
			$xml_page['pagecount']   = $page_array[2];
			$xml_page['pagesize']    = $page_array[3];
			$xml_page['pageindex']   = $page_array[1];
			$array_url['p']          = '{!page!}';
			$array_tpl['pageurl']    = U('Admin-Collect/Gxcms',$array_url);
			$array_tpl['pagelist']   = '共'.$xml_page['recordcount'].'条数据&nbsp;页次:'.$xml_page['pageindex'].'/'.$xml_page['pagecount'].'页&nbsp;'.get_cms_page_css($xml_page['pageindex'],$xml_page['pagecount'],5,$array_tpl['pageurl'],'pagego(\''.$array_tpl['pageurl'].'\','.$xml_page['pagecount'].')');
			//组合绑定分类
			preg_match_all('/<ty id="([0-9]+)">([\s\S]*?)<\/ty>/',$xml,$array_list);
			foreach($array_list[1] as $key=>$value){
				$list[$key]['cid']    = $value;
				$list[$key]['cname']  = $array_list[2][$key];
				$list[$key]['bind_id']= $array_url['fid'].'_'.$value;
			}
			if($array_url['action']){
				preg_match_all('/<video><last>([\s\S]*?)<\/last><id>([0-9]+)<\/id><tid>([0-9]+)<\/tid><name><\!\[CDATA\[([\s\S]*?)\]\]><\/name><type>([\s\S]*?)<\/type><pic>([\s\S]*?)<\/pic><lang>([\s\S]*?)<\/lang><area>([\s\S]*?)<\/area><year>([\s\S]*?)<\/year><state>([\s\S]*?)<\/state><note><\!\[CDATA\[([\s\S]*?)\]\]><\/note><actor><\!\[CDATA\[([\s\S]*?)\]\]><\/actor><director><\!\[CDATA\[([\s\S]*?)\]\]><\/director><dl>([\s\S]*?)<\/dl><des><\!\[CDATA\[([\s\S]*?)\]\]><\/des><\/video>/',$xml,$array_vod);
			}else{
				preg_match_all('/<video><last>([\s\S]*?)<\/last><id>([0-9]+)<\/id><tid>([0-9]+)<\/tid><name><\!\[CDATA\[([\s\S]*?)\]\]><\/name><type>([\s\S]*?)<\/type><dt>([\s\S]*?)<\/dt><note><\!\[CDATA\[([\s\S]*?)\]\]><\/note><actor><\!\[CDATA\[([\s\S]*?)\]\]><\/actor>/',$xml,$array_vod);
			}
			//组合数据
			foreach($array_vod[1] as $key=>$value){
				$vod[$key]['addtime']  = $value;
				$vod[$key]['id']       = $array_vod[2][$key];
				$vod[$key]['iid']      = $array_vod[2][$key];
				$vod[$key]['cid']      = getbindval($array_url['fid'].'_'.$array_vod[3][$key]);
				$vod[$key]['title']    = htmlspecialchars_decode($array_vod[4][$key]);
				$vod[$key]['cname']    = $array_vod[5][$key];
				$vod[$key]['picurl']   = $array_vod[6][$key];
				$vod[$key]['language'] = $array_vod[7][$key];
				$vod[$key]['area']     = $array_vod[8][$key];
				$vod[$key]['year']     = $array_vod[9][$key];	
				$vod[$key]['serial']   = $array_vod[10][$key];		
				$vod[$key]['intro']    = htmlspecialchars_decode($array_vod[11][$key]);
				if($array_url['action']){
					$vod[$key]['actor']= htmlspecialchars_decode($array_vod[12][$key]);
				}else{
					$vod[$key]['actor']= htmlspecialchars_decode($array_vod[8][$key]);
				}
				$vod[$key]['director'] = htmlspecialchars_decode($array_vod[13][$key]);
				$vod[$key]['content']  = htmlspecialchars_decode($array_vod[15][$key]);
				$vod[$key]['inputer']  = $array_url['fid'].'_'.$vod[$key]['id'];
				$vod[$key]['reurl']    = str_replace('@','/',$array_url['reurl']).$vod[$key]['id'];
				preg_match_all('/<dd flag="([\s\S]*?)"><\!\[CDATA\[([\s\S]*?)\]\]><\/dd>/',$array_vod[14][$key],$url_arr);
				$vod[$key]['playurl']  = htmlspecialchars_decode($this->xml_url_replace(implode('$$$',$url_arr[2])));			
			}
			$array['url']       = $array_url; //远程URL变量
			$array['tpl']       = $array_tpl; //本地模板变量
			$array['page']      = $xml_page; //远程分页信息
			$array['listclass'] = $list; //远程分类变量
			$array['listvod']   = $vod; //远程数据变量
			return $array;
		}else{
			return false;
		}
	}
	
	
	
	/**
	 * 资源站为gxcms系统
	 * 
	 * @param array $array_url
	 * @return 
	 */
    public function ziyuan_gx($array_url){
		//组合资源库URL地址并获取XML资源
		$array_tpl['httpurl'] = $array_url['xmlurl'].'/index.php?s=plus/api/xml/cms/gx/vodids/'.$array_url['vodids'].'/cid/'.$array_url['cid'].'/wd/'.urlencode($array_url['wd']).'/h/'.$array_url['h'].'/p/'.$array_url['p'];
		//还原资源站网址
		$array_tpl['httpurl'] = str_replace('@','/',$array_tpl['httpurl']);
		$xml = get_collect_file($array_tpl['httpurl']);
		if ($xml) {
			return $this->ff_gx_xml($array_url,$xml);
		}else{
			return false;
		}
	}
	
	/**
	 * 资源站为飞飞影视系统
	 * 
	 * @param array $array_url
	 * @return 
	 */
	public function ziyuan_feifei($array_url){
		$array_tpl['httpurl'] = $array_url['xmlurl'].'/index.php?s=plus-api-xml-cms-ff-action-'.$array_url['action'].'-vodids-'.$array_url['vodids'].'-cid-'.$array_url['cid'].'-wd-'.urlencode($array_url['wd']).'-h-'.$array_url['h'].'-p-'.$array_url['p'];
		$array_tpl['httpurl'] = str_replace('@','/',$array_tpl['httpurl']);//还原目标网址
		$xml = get_collect_file($array_tpl['httpurl']);
		if ($xml) {
			return $this->ff_gx_xml($array_url,$xml);
		}else{
			return false;
		}
	}
	
	/**
	 * 将资源站抓取的值整理成数组变量(飞飞+光线)
	 * 
	 * @param  $array_url
	 * @param  $xml
	 */
	public function ff_gx_xml($array_url,$xml){
		//组合分页信息
		preg_match('<list page="([0-9]+)" pagecount="([0-9]+)" pagesize="([0-9]+)" recordcount="([0-9]+)">',$xml,$page_array);
		$xml_page['recordcount'] = $page_array[4];
		$xml_page['pagecount']   = $page_array[2];
		$xml_page['pagesize']    = $page_array[3];
		$xml_page['pageindex']   = $page_array[1];
		$array_url['p']          = '{!page!}';
		$array_tpl['pageurl']    = U('Admin-Collect/Gxcms',$array_url);
		$array_tpl['pagelist']   = '共'.$xml_page['recordcount'].'条数据&nbsp;页次:'.$xml_page['pageindex'].'/'.$xml_page['pagecount'].'页&nbsp;'.get_cms_page_css($xml_page['pageindex'],$xml_page['pagecount'],5,$array_tpl['pageurl'],'pagego(\''.$array_tpl['pageurl'].'\','.$xml_page['pagecount'].')');
		
		//组合绑定分类
		preg_match_all('/<ty id="([0-9]+)">([\s\S]*?)<\/ty>/',$xml,$array_list);
		foreach($array_list[1] as $key=>$value){
			$list[$key]['cid']     = $value;
			$list[$key]['cname']   = $array_list[2][$key];
			$list[$key]['bind_id'] = $array_url['fid'].'_'.$value;
		}
		
		//组合单个影视数据
		preg_match_all('/<video><last>([\s\S]*?)<\/last><id>([0-9]+)<\/id><tid>([0-9]+)<\/tid><name><\!\[CDATA\[([\s\S]*?)\]\]><\/name><type>([\s\S]*?)<\/type><dt>([\s\S]*?)<\/dt><pic>([\s\S]*?)<\/pic><lang>([\s\S]*?)<\/lang><area>([\s\S]*?)<\/area><year>([\s\S]*?)<\/year><state>([\s\S]*?)<\/state><note><\!\[CDATA\[([\s\S]*?)\]\]><\/note><actor><\!\[CDATA\[([\s\S]*?)\]\]><\/actor><director><\!\[CDATA\[([\s\S]*?)\]\]><\/director><dl>([\s\S]*?)<\/dl><des><\!\[CDATA\[([\s\S]*?)\]\]><\/des><reurl>([\s\S]*?)<\/reurl><\/video>/',$xml,$array_vod);
		foreach($array_vod[1] as $key=>$value){
			$vod[$key]['addtime']  = $value;
			$vod[$key]['id']       = $array_vod[2][$key];
			$vod[$key]['iid']      = $array_vod[2][$key];
			$vod[$key]['cid']      = getbindval($array_url['fid'].'_'.$array_vod[3][$key]);
			$vod[$key]['title']    = htmlspecialchars_decode($array_vod[4][$key]);
			$vod[$key]['cname']    = $array_vod[5][$key];
			$vod[$key]['picurl']   = $array_vod[7][$key];
			$vod[$key]['language'] = $array_vod[8][$key];
			$vod[$key]['area']     = $array_vod[9][$key];
			$vod[$key]['year']     = $array_vod[10][$key];	
			$vod[$key]['serial']   = $array_vod[11][$key];		
			$vod[$key]['intro']    = htmlspecialchars_decode($array_vod[12][$key]);
			$vod[$key]['actor']    = htmlspecialchars_decode($array_vod[13][$key]);
			$vod[$key]['director'] = htmlspecialchars_decode($array_vod[14][$key]);
			$vod[$key]['content']  = htmlspecialchars_decode($array_vod[16][$key]);
			$vod[$key]['inputer']  = $array_url['fid'].'_'.$vod[$key]['id'];
			$vod[$key]['reurl']    = $array_vod[17][$key];
			if(!$vod[$key]['reurl']){
				$vod[$key]['reurl']= str_replace('@','/',$array_url['reurl']).$vod[$key]['id'];
			}
			preg_match_all('/<dd flag="([\s\S]*?)"><\!\[CDATA\[([\s\S]*?)\]\]><\/dd>/',$array_vod[15][$key],$url_arr);
			$vod[$key]['playurl'] = htmlspecialchars_decode(implode('$$$',$url_arr[2]));			
		}
		$array['url']       = $array_url; //远程URL变量
		$array['tpl']       = $array_tpl; //本地模板变量
		$array['page']      = $xml_page; //远程分页信息
		$array['listclass'] = $list; //远程分类变量
		$array['listvod']   = $vod; //远程数据变量
		return $array;
	}
	

	/**
	 * XML方式获取到的资源站地址转化为gxcms的地址
	 * 
	 * @param  string $playurl
	 * @return string $gxurl
	 */
	public function xml_url_replace($playurl){
		$array_url = array();
		$arr_ji    = explode('#',$playurl);
		foreach($arr_ji as $key=>$value){
			$urlji = explode('$',$value);
			if(count($urlji)==3){
				$array_url[$key] = $urlji[0].'$'.$urlji[1];
			}else{
				$array_url[$key] = $urlji[0];
			}
		}
		return implode(chr(13),$array_url);	
	}
	

	/**
	 * 采集影片入库
	 * @param array   $vod  新采集数据
	 * @param boolean $must 是否强制更新
	 */
    public function xml_insert($vod,$must){
		if(empty($vod['title']) || empty($vod['playurl'])){
			return '影片名称或播放地址为空，不做处理!';
		}
		//未入库标识
		if ( !$vod['cid'] ) {
			//$vod['cid'] = 999;
			return '未匹配到对应栏目分类，不做处理!';
		}
		//过滤常规重复字符
		$vod['title']    = str_replace(array('HD','BD','DVD','VCD','TS','【完结】','【】','[]','()'),'',$vod['title']);
		$vod['actor']    = str_replace(array(',','/','，','|','、'),' ',$vod['actor']);
		$vod['director'] = str_replace(array(',','/','，','|','、'),' ',$vod['director']);
		//入库开始
		unset($vod['id']);
		$array = $this->VdoDB->field('id,cid,title,inputer,playurl')->where('reurl="'.$vod['reurl'].'"')->find();
		if($array){
			//有来源.检测影片地址是否发生变化
			return $this->xml_update($vod,$array,$must);
		}else{
			//无来源.检测是否有相同影片(需防止同名的电影与电视冲突)
			$array = $this->VdoDB->field('id,cid,title,intro,actor,inputer,playurl')->where('title="'.$vod['title'].'"')->find();
			if($array){
				//无主演时直接更新该影片
				if(empty($vod['actor'])){
					return $this->xml_update($vod,$array,$must);
				}
				//演员完全相等时更新该影片
				if($array['actor'] == $vod['actor']){
					return $this->xml_update($vod,$array,$must);
				}
				//有相同演员时更新该影片
				$arr_actor_1 = explode(' ',$vod['actor']);
				$arr_actor_2 = explode(' ',str_replace(array(',','/','，','|','、'),' ',$array['actor']));
				if(array_intersect($arr_actor_1,$arr_actor_2)){
					return $this->xml_update($vod,$array,$must);
				}
			}
			//其它条件将新加影片，添加前做相似条件判断
			if(C('web_collect_num')){
				$length = ceil(strlen($vod['title'])/3)-intval(C('web_collect_num'));
				if($length >= 2){
					$where['title'] = array('like','%'.get_replace_html($vod['title'],0,$length).'%');
					$array = $this->VdoDB->field('id,title,inputer,actor,playurl')->where($where)->find();
					
					//主演完全相同则更新
					if(!empty($array['actor']) && !empty($vod['actor']) ){
					 //对比
					$arr_actor_1 = explode(' ',$vod['actor']);
				    $arr_actor_2 = explode(' ',str_replace(array(',','/','，','|','、'),' ',$array['actor']));
				    if(!array_diff($arr_actor_1,$arr_actor_2) && !array_diff($arr_actor_2,$arr_actor_1)){//若差集为空
					return $this->xml_update($vod,$array,$must);
				    }
				    //主演不完全相同则添加
					}
					
					if(!in_array($vod['inputer'],$array) && $array){//inputer不同则隐藏
					$vod['status'] = -1;
					}
				}
			}
			//添加影片开始
			if (C('upload_http')) {
				$down = D('Down');
				$vod['picurl'] = $down->down_img($vod['picurl']);
			}
			$this->VdoDB->data($vod)->add();
			return '视频添加成功！';
		}
    }

	/**
	 * 影片更新检测 
	 * 
	 * @param array   $vod    新采集数据
	 * @param array   $array  数据库查询获取数据
	 * @param boolean $must
	 */
	public function xml_update($vod,$array,$must=false){
		if('gxcms' == $array['inputer']){
			return '站长手动锁定，退出更新！';
		}
		if(!$must){//是否强制更新资料
			if ($array['playurl'] == $vod['playurl']) {
			return '播放地址未变化，退出更新！';
			}
			$count_vod   = count(explode(chr(13),($vod['playurl'])));
			$count_array = count(explode(chr(13),trim($array['playurl'])));
			if($count_vod < $count_array){
			return '小于数据库集数，退出更新！';
			}
		}else{
			if (C('upload_http')) {
				$down = D('Down');
				$edit['picurl'] = $down->down_img($vod['picurl']);
			}else{
				$edit['picurl'] = $vod['picurl'];
			}
			$edit['title']    = $vod['title'];
			$edit['intro']    = $vod['intro'];
			$edit['actor']    = $vod['actor'];
			$edit['director'] = $vod['director'];
			$edit['area']     = $vod['area'];
			$edit['language'] = $vod['language'];
			$edit['reurl']    = $vod['reurl'];		
		}
		$edit['cid']     = $vod['cid'];
		$edit['serial']  = $vod['serial'];
		$edit['playurl'] = $vod['playurl'];
		$edit['addtime'] = time();
		$edit['reurl']   = $vod['reurl'];
		$this->VdoDB->where('id='.$array['id'])->data($edit)->save();	
		return '播放地址更新成功！';
	}			
}
?>