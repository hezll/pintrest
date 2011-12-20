<?php
class HomeAction extends CmsAction{
    public function _initialize(){
		parent::_initialize();
		C('TOKEN_NAME','__gxcmsform__');
		$this->assign($this->tags_style());
    }
    
    public function _empty(){
    	$this->display('views/tips/error.html');
    	//echo "error request.";
    	exit;
    }
}
?>