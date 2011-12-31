<?php
class MyAction extends HomeAction{
    public function index(){
		$this->show();
	}
    public function show(){
		$id = !empty($_GET['id'])?$_GET['id']:'hot';
		$this->display('my_'.trim($id));
	}					
}
?>