<?php
class AdminAction extends CmsAction{
    public function _initialize(){
		   parent::_initialize();
		   
		//检查登录
		if (!$_SESSION[C('USER_AUTH_KEY')]) {
			$this->assign('jumpUrl',C('cms_admin').'?s=Admin/Login');
			$this->error('对不起,您还没有登录,请先登录！');
		}
		//检查权限 不需要验证操作的除外
		if (!in_array(strtolower(ACTION_NAME),explode(',',C('NOT_AUTH_ACTION')))) {
			// 检索当前模块是否需要认证
			$model_id = array_search(MODULE_NAME,explode(',',C('REQUIRE_AUTH_MODULE')));
			if (is_int($model_id)) {
				$usertype = explode(',',$_SESSION['usertype']);
				if (!$usertype[$model_id]) {
					if (ACTION_NAME=='Downimg'){
						$this->assign('jumpUrl',C('cms_admin').'?s=Admin/Index/Index');
					}
					$this->error('对不起您没有管理该模块的权限,请联系超级管理员授权！');
				}
			}
		}
    }
	//远程下载图片
	public function down_img($url,$mid='video'){
       $chr       = strrchr($url,'.');
	   $imgUrl    = uniqid();
	   $imgPath   = $mid.'/'.date(C('upload_style'),time()).'/';	
	   $imgPath_s = './'.C('upload_path').'-s/'.$imgPath;
	   $filename  = './'.C('upload_path').'/'.$imgPath.$imgUrl.$chr;
	   $get_file  = get_collect_file($url);
	   if ($get_file) {
		   write_file($filename,$get_file);
		   //是否添加水印
		   if(C('upload_water')){
			   import('ORG.Util.Image');
			   Image::water($filename,C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
		   }		   
		   //是否生成缩略图
		   if(C('upload_thumb')){
			   mkdirss($imgPath_s);
			   import('ORG.Util.Image');
			   Image::thumb($filename,$imgPath_s.$imgUrl.$chr,'',C('upload_thumb_w') ,C('upload_thumb_h'),true);
		   }
		   //是否上传远程
		   if (C('upload_ftp')) {
			   $this->ftp_upload($imgPath.$imgUrl.$chr);
		   }
		   return $imgPath.$imgUrl.$chr;
	   }else{
			return $url;
	   } 
	}
	//远程附件操作
	public function ftp_upload($imgurl){
		Vendor('FtpUpload.FtpUpload');
		$config_ftp = array(
			'ftp_host' =>C('upload_ftp_host'),
			'ftp_port' =>C('upload_ftp_port'),
			'ftp_user' =>C('upload_ftp_user'),
			'ftp_pwd'  =>C('upload_ftp_pass'),
			'ftp_dir'  =>C('upload_ftp_dir')
		);
		$ftp = new _ftp_upload();
		$ftp->config($config_ftp);
		$ftp->connect();	
		$ftpimg = $ftp->put(C('upload_path').'/'.$imgurl,C('upload_path').'/'.$imgurl);		
		if (C('upload_thumb')) {
			$ftpimg_s = $ftp->put(C('upload_path').'-s/'.$imgurl, C('upload_path').'-s/'.$imgurl);
		}
		if ($ftpimg) {
			@unlink(C('upload_path').'/'.$imgurl);
		}		
		if ($ftpimg_s) {
			@unlink(C('upload_path').'-s/'.$imgurl);
		}
		$ftp->bye();
	}	
}
?>