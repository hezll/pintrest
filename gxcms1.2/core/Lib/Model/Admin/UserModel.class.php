<?php 
import('AdvModel');
class UserModel extends AdvModel {
	protected $_validate = array(
		array('username','require','会员名称必须填写！',1),
		array('userpwd','require','会员密码不能为空！',1,'',1),
		array('reuserpwd','userpwd','两次输入的密码不一致,请重新输入！',1,'confirm','',3),
		array('email','email','会员邮箱格式错误！',1),
		array('money','check_integer','用户剩余点数格式错误！',1,'callback'),
		array('lognum','check_integer','登录次数格式错误！',2,'callback'),
		array('qq','check_qq','QQ号码格式错误！',2,'callback',3),		
		array('username','','用户名称已经存在！',1,'unique',1),
		array('email','','邮箱名称已经存在！',1,'unique',1),
	);
	// 自动填充设置
	protected $_auto = array(
		array('username','trim',1,'function'),
		array('username','remove_xss',1,'function'),
		array('joinip','get_client_ip',1,'function'),
		array('logip','get_client_ip',1,'function'),
		array('userpwd','check_pwd',3,'callback'),
		array('jointime','time',1,'function'),
		array('logtime','time',1,'function'),
		array('duetime','strtotime',3,'function'),
		array('jointime','strtotime',2,'function'),
		array('logtime','strtotime',2,'function'),	
	);
	public function check_pwd(){
		if(empty($_POST['userpwd'])){
		    return false;
		}else{
		    return md5(trim($_POST['userpwd']));
		}		
	}
	public function check_qq($value){
		return preg_match("/^[1-9]\d{4,10}$/",$value) === 1;		
	}
	public function check_integer($value){
		return preg_match("/^[1-9]?\d+$/",$value) === 1;		
	}
}
?>