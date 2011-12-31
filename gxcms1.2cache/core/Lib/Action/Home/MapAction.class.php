<?php
class MapAction extends HomeAction{
    public function index(){
		$this->lists();
	}
    public function lists(){
		$id    = !empty($_GET['id'])?trim($_GET['id']):'rss';
		$limit = !empty($_GET['limit'])?intval($_GET['limit']):20;
		$rs = M("Video");
		$list = $rs->order('addtime desc')->limit($limit)->select();
		foreach($list as $key=>$val){
			$list[$key]['readurl'] = get_read_url('video',$val['id'],$val['cid']);
			$list[$key]['playurl'] = get_play_url($val['id'],$val['cid'],1);
		}		
		$this->assign('listmap',$list);
		$this->display('./views/xml/'.$id.'.html','utf-8','text/xml'); 
	}						
}
?>