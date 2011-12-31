<?php
/**
 * @name    模板管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class TplAction extends AdminAction{
	//获取当前路径
	public function dirpath(){
		$id = $_GET['id'];
		if ($id) {
			$dirpath = str_replace('*','/',$id);
		}else{
			$dirpath = './template';
		}
		if (!strpos($dirpath,'template')) {
			$this->error('不在模板文件夹请求的范围内!');
		}
		return $dirpath;	
	}
	//获取模板上一层路径
	public function dirup(){
		$id = trim($_GET['id']);
		if ($id) {
			return substr($id,0,strrpos($id, '*'));
		}else{
			return false;
		}
	}		
	// 显示模板
    public function show(){
		$dirpath = $this->dirpath();
		$dirup   = $this->dirup();
		import("ORG.Io.Dir");
		$dir = new Dir($dirpath);
		$dirlist = $dir->toArray();
		if (strpos($dirup,'Template') > 0){
			$this->assign('dirup',$dirup);
		}
		if (empty($dirlist)){
			$this->error('该文件夹下面没有任何文件!');
		}
		if($_GET['mytpl']){
			foreach($dirlist as $key=>$value){
				if (strpos($value['filename'],'my_')===false){
					unset($dirlist[$key]);
				}
			}
		}
		$_SESSION['tpl_reurl'] = C('cms_admin').'?s=Admin/Tpl/Show/id/'.str_replace('/','*',$dirpath);
		if($dirup && $dirup!='.'){
			$this->assign('dirup',$dirup);
		}	
		$this->assign('dir',list_sort_by($dirlist,'mtime','desc'));
		$this->assign('dirpath',$dirpath);
		$this->display('./views/admin/tpl_show.html');
    }	
	// 编辑模板
	public function add(){
		$tpl = str_replace('*','/',str_replace('@','.',trim($_GET['id'])));
		if (empty($tpl)) {
			$this->error('模板名不能为空!');
		}
		$content = read_file($tpl);
		$this->assign('filename',$tpl);
		$this->assign('content',$content);
		$this->display('./views/admin/tpl_add.html');
	}
	// 更新模板
	public function update(){
		$filename = trim($_POST['filename']);
		$content  = stripslashes($_POST['content']);
		if (!testwrite(substr($filename,0,strrpos($filename,'/')))){
			$this->error('在线编辑模板需要给'.TEMPLATE_PATH.'添加写入权限!');
		}
		if (empty($filename)) {
			$this->error('模板文件名不能为空!');
		}
		if (empty($content)) {
			$this->error('模板内容不能为空!');
		}
		$tpl = $filename;
		write_file($tpl,$content);
		if (!empty($_SESSION['tpl_reurl'])) {
			$this->assign("jumpUrl",$_SESSION['tpl_reurl']);
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Tpl/Show');
		}
		$this->success('恭喜您,模板更新成功！');
	}
	// 删除
    public function del(){
		$id = str_replace('*','/',str_replace('@','.',trim($_GET['id'])));
		if (!substr(sprintf("%o",fileperms($id)),-3)){
			$this->error('无删除权限!');
		}
		@unlink($id);
		if (!empty($_SESSION['tpl_reurl'])) {
			$this->assign("jumpUrl",$_SESSION['tpl_reurl']);
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Tpl/Show');
		}
		$this->success('删除文件成功！');
    }						
}
?>