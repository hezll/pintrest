<?php 
import('AdvModel');
class UsergroupModel extends AdvModel {
	protected $_validate=array(
		array('name','require','分组名称必须填写！',1,'',1),
		array('point','require','消费点数必须填写！',1,'',1),
		array('point','number','消费点数请输入数字！',1,'',3),
		array('name','','用户组名称已经存在！',1,'unique',1),
	);
	// 自动填充设置
	protected $_auto = array(
		array('cid','check_cid',3,'callback'),
		array('tid','check_tid',3,'callback'),
	);
	public function check_cid(){
		if (is_array($_POST['cid'])) {
		    return implode(',',$_POST['cid']);
		}else{
		    return false;
		}		
	}
	public function check_tid(){
		if (is_array($_POST['tid'])) {
		    return implode(',',$_POST['tid']);
		}else{
		    return false;
		}		
	}			
}
?>