<?php
/**
 * @name    上传附件管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class UploadAction extends AdminAction{
	// 列表		
    public function show(){
		$this->display('./views/admin/upload.html');
    }
	// 上传
	public function upload(){
		echo('<div style="font-size:12px; height:30px; line-height:30px">');
		$uppath   = './'.C('upload_path').'/';
		$uppath_s = './'.C('upload_path').'-s/';
		$mid      = trim($_POST['mid']);
		$fileback = !empty($_POST['fileback']) ? trim($_POST['fileback']) : 'picurl';
		if ($mid) {
			$uppath.= $mid.'/';
			$uppath_s.= $mid.'/';
			$backpath = $mid.'/';
		}
		import("ORG.Net.UploadFile");
		$up = new UploadFile();
		//$up->maxSize = 3292200;
		$up->savePath      = $uppath;
		$up->saveRule      = uniqid;
		$up->uploadReplace = true;
		$up->allowExts     = explode(',',C('cms_exts'));
		$up->autoSub       = true;
		$up->subType       = date;
		$up->dateFormat    = C('upload_style');
        if (!$up->upload()) {
			$error = $up->getErrorMsg();
			if($error == '上传文件类型不允许'){
				$error .= ',可上传<font color=red>'.C('cms_exts').'</font>';
			}
			exit($error.' [<a href="?s=Admin/Upload/Show/mid/'.$mid.'/fileback/'.$fileback.'">重新上传</a>]');
			//dump($up->getErrorMsg());
		}
		$uploadList = $up->getUploadFileInfo();
		//是否添加水印
		if (C('upload_water')) {
		   import("ORG.Util.Image");
		   Image::water($uppath.$uploadList[0]['savename'],C('upload_water_img'),'',C('upload_water_pct'),C('upload_water_pos'));
		}
		//是否生成缩略图
		if (C('upload_thumb')) {
		   $thumbdir = substr($uploadList[0]['savename'],0,strrpos($uploadList[0]['savename'], '/'));
		   mkdirss($uppath_s.$thumbdir);
		   import("ORG.Util.Image");
		   Image::thumb($uppath.$uploadList[0]['savename'],$uppath_s.$uploadList[0]['savename'],'',C('upload_thumb_w'),C('upload_thumb_h'),true);
		}
		//是否远程图片
		if (C('upload_ftp')) {
			$img = D('Down');
			$img->ftp_upload($backpath.$uploadList[0]['savename']);
		}
		echo "<script type='text/javascript'>parent.document.getElementById('".$fileback."').value='".$backpath.$uploadList[0]['savename']."';</script>";
		echo '文件<a href="'.$uppath.$uploadList[0]['savename'].'" target="_blank"><font color=red>'.$uploadList[0]['savename'].'</font></a>上传成功　[<a href="?s=Admin/Upload/Show/mid/'.$mid.'/fileback/'.$fileback.'">重新上传</a>]';
		echo '</div>';
	}
	// 本地附件展示
    public function fileshow(){
		$id = $_GET['id'];
		if ($id) {
			$dirup   = substr($id,0,strrpos($id, '*'));
			$dirpath = str_replace('*','/',$id);
		}else{
			$dirpath = './'.C('upload_path');
		}
		if (!strpos($dirpath,trim(C('upload_path')))) {
			$this->error('不在附件文件夹的范围内!');
		}		
		import("ORG.Io.Dir");
		$dir = new Dir($dirpath);
		$dirlist = $dir->toArray();
		if (strpos($dirup,C('upload_path')) > 0){
			$this->assign('dirup',$dirup);
		}
		$this->assign('dir',$dirlist);
		$this->display('./views/admin/upload_fileshow.html');
    }
	// 删除本地附件
    public function filedel(){
		$id = $_GET['id'];
		if ($id) {
			$dirpath = str_replace('*','/',$id);
			@unlink($dirpath);
			@unlink(str_replace(C('upload_path').'/',C('upload_path').'-s/',$dirpath));
			$this->success('删除附件成功！');
		}
    }			
}
?>