<?php 
import('AdvModel');
class SpecialModel extends AdvModel {
	protected $_validate=array(
		array('title','require','专题名称必须填写！',1),
		array('hits','check_integer','专题人气格式错误！',2,'callback'),
	);
	protected $_auto=array(
		array('addtime','m_addtime',3,'callback'),
	);	
	public function m_addtime(){
		if ($_POST['checktime']) {
			return time();
		}else{
			return strtotime($_POST['addtime']);
		}
	}
	public function check_integer($value){
		return preg_match("/^[1-9]?\d+$/",$value) === 1;		
	}			
}
?>