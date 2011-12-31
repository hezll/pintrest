<?php
/**
 * @name    采集模块
 * @package GXCMS.Administrator
 */
class CollectAction extends AdminAction{
	//采集库列表
    public function show(){
		$this->assign('jumpurl',F('_collect/xucai'));
        $this->display('views/admin/collect_show_list.html');
    }
	// 资源站分支
    public function gxcms(){
		$collect = D('Xmlzy');
		$ziyuan = !empty($_GET['ziyuan'])?$_GET['ziyuan']:'gxcms';
		$xml = $collect->xml_httpurl($ziyuan);
		if ($xml) {
			$this->xmlmdb($xml);
		}else{
			$this->error("获取第三方影视列表失败!");
		}
    }
	//分解数据(判断采集入库or展示绑定)
	public function xmlmdb($xml){
		$collect   = D('Xmlzy');
		$array_url = $xml['url'];
		$array_tpl = $xml['tpl'];
		$xml_page  = $xml['page'];
		$list_class= $xml['listclass'];
		$list_vod  = $xml['listvod'];
		//是否采集入库
		if($array_url['action']){
			$page = $array_url['page'];
			echo '<style type="text/css">div{font-size:12px;color: #333333;margin:6px;}span{font-weight:bold;color:#FF0000}</style>';
			echo'<div id="show"><div>当前采集任务<font color=green><strong>'.$page.'</strong></font>/<span class="green">'.$xml_page['pagecount'].'</span>页，本页共需要采集数据<span>'.$xml_page['pagesize'].'</span>个 '.$array_tpl['httpurl'].'</div>';
			foreach($list_vod as $key=>$vod){
				$vod['addtime'] = time();
				$vod['stars']   = 1;
				$vod['letter']  = get_letter($vod['title']);
				$vod['hits']    = mt_rand(0,C('web_admin_hits'));
				$vod['score']   = mt_rand(1,C('web_admin_score'));
				$vod['scoreer'] = mt_rand(1,C('web_admin_score'));
				$vod['up']      = mt_rand(1,C('web_admin_updown'));
				$vod['down']    = mt_rand(1,C('web_admin_updown'));
				$vod['inputer'] = 'collect_'.$array_url['fid'];
				//地址入库
				echo '<div><span>'.($key+1).'</span> ['.get_channel_name($vod['cid']).'] '.$vod['title'].' <font color=blue>';
				echo $collect->xml_insert($vod,$array_url['pic']);
				echo '</font></div>';
				ob_flush();flush();
			}
			if('all' == $array_url['action'] || 'day' == $array_url['action']){
				if($page < $xml_page['pagecount']){
					$jumpurl = str_replace('{!page!}',($page+1),$array_tpl['pageurl']);
					//缓存断点续采
					F('_collect/xucai',$jumpurl);
					//跳转到下一页
					echo '<div><span>'.C('url_create_time').'</span>秒后将自动采集下一页！<meta http-equiv="refresh" content='.C('url_create_time').';url='.$jumpurl.'></div>';
				}else{
					//清除断点续采
					F('_collect/xucai',NULL);
					echo '<div>所有采集任务已经完成，返回<a href="?s=Admin/Video/Show">[视频管理中心</a>]，查看<a href="?s=Admin/Video/Show/status/-1">[相似未审核的影片</a>]!</div>';					
				}
			}else{
				echo '<div>所有采集任务已经完成，返回<a href="?s=Admin/Video/Show">[视频管理中心</a>]，查看<a href="?s=Admin/Video/Show/status/-1">[相似未审核的影片</a>]!</div>';	
			}	
		}else{
			$array_url['vodids'] = '';
			$this->assign($array_url);
			$this->assign($array_tpl);
			$this->assign('list_class',$list_class);
			$this->assign('list_vod',$list_vod);
			$this->display('./views/admin/collect_show.html');
		}
	}
	// 检测第三方资源分类是否绑定
    public function setbind(){
		$rs   = M("Channel");
		$list = $rs->field('id,pid,mid,cname')->where('mid = 1')->order('id asc')->select();
		foreach($list as $key=>$value){
			if(!get_channel_son($list[$key]['id'])){
				unset($list[$key]);
			}
		}
		$this->assign('list',$list);
		$this->display('./views/admin/collect_setbind.html');
	}
	// 存储第三方资源分类绑定
    public function insertbind(){
		$bindcache = F('_collect/channel');
		if (!is_array($bindcache)) {
			$bindcache = array();
			$bindcache['0_0'] = 0;
		}
		$bindkey = trim($_GET['bind']);
		$bindinsert[$bindkey] = intval($_GET['cid']);
		$bindarray = array_merge($bindcache,$bindinsert);
		F('_collect/channel',$bindarray);
		exit('ok');
	}			
}
?>