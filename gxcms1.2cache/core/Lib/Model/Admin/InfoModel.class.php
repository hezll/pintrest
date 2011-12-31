<?php 
import('AdvModel');
class InfoModel extends AdvModel {
	protected $_validate=array(
	    array('cid','number','请选择分类！',1,'',3),
		array('cid','get_channel_son','请选择当前分类下面的子类栏目！',1,'function',3),
		array('title','require','新闻标题必须填写！',1,'',3),
	);
	protected $_auto=array(
		array('title','trim',3,'function'),
		array('remark','get_remark',3,'callback'),
		array('letter','a_letter',3,'callback'),
		array('addtime','a_addtime',3,'callback'),
	);
	// 回调方法
	public function get_remark(){
		if(empty($_POST['remark'])){
			return get_replace_html(trim($_POST['content']),0,100,'utf-8',false);
		}else{
			return trim($_POST['remark']);
		}
	}
	public function a_letter(){
		return get_letter(trim($_POST['title']));
	}
	public function a_addtime(){
		if ($_POST['checktime']) {
			return time();
		}else{
			return strtotime($_POST['addtime']);
		}
	}		
}
?>