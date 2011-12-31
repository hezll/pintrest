<?php
/**
 * @name    重复或无效数据检测模块
 * @package GXCMS.Administrator
 */
class DatacheckAction extends AdminAction{
	private $DCModel;
    public function _initialize(){
	 	parent::_initialize();
	 	C('TMPL_FILE_NAME','./views/tools/..');//模板目录
	 	$this->DCModel =D('Admin.Datacheck');
    }
	
	public function VideoCheck(){//重复影片检测
		if($_REQUEST['check_sub']){
		$Get=$this->DCModel->GetParam();
		if(!empty($Get['len'])){
		$result=$this->DCModel->RepeatCheck($Get);	
		$this->assign('result',$result);
		$this->assign('list_channel_video',F('_gxcms/channelvideo'));
		}
		}
	    $this->display('video_check');
	    
	}
	
	
	public function ImgClear(){//无效图片清除
		header("Content-type: text/html; charset=utf-8");
		$Clear=$this->DCModel->InvalidImg();
		echo "<div style='font-size:14px;margin:50px;color:#393;'>";
		if($Clear){
			echo '<div>恭喜！所有无效图片清除完毕！</div>';
		}else{
			echo $this->DCModel->getError();
		}
		echo '</div>';
	}
}
?>