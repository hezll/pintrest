<?php
/**
 * @name    系统管理后台入口
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class IndexAction extends AdminAction{
	
	public function _initialize(){
	 	parent::_initialize();
	 	C('TMPL_FILE_NAME','./views/admin/..');
	 }
 
    public function index(){
        $this->display('index2');
    }
	
    public function left(){
        $this->display('left2');
    }
	
    public function top(){
        $this->display('top');
    }	
	
    public function main(){
		$this->create_channel();
        $this->display('main');
    }

    public function menuMap(){
    	$this->display('menu_map');
    }
}
?>