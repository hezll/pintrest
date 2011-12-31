<?php
/**
 * @name    图片相关模块
 * @package GXCMS.Administrator
 *
 */
class DownModel extends Model {	
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
			'ftp_host'=>C('upload_ftp_host'),
			'ftp_port'=>C('upload_ftp_port'),
			'ftp_user'=>C('upload_ftp_user'),
			'ftp_pwd' =>C('upload_ftp_pass'),
			'ftp_dir' =>C('upload_ftp_dir'),
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