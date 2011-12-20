<?php
class Top10Action extends HomeAction{
    public function index(){
		$this->lists();
	}
    public function lists(){
		$this->assign('webtitle','排行榜'.'-'.C('web_name'));
		$this->assign('navtitle','<a href="'.C('web_path').'">首页</a> &gt; <span>排行榜</span>');
		$this->display('top10');
	}			
}
?>