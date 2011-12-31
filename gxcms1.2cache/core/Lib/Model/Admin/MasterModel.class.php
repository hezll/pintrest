<?php 
import('AdvModel');
class MasterModel extends AdvModel {
	protected $_validate=array(
		array('user','require','管理员名称必须填写！',1,'',1),
		array('pwd','require','管理员密码必须填写！',1,'',1),
		array('repwd','pwd','两次输入的密码不一致,请重新输入！',1,'confirm','',3),
		array('user','','帐号名称已经存在！',1,'unique',1),
	);
	protected $_auto=array(
		array('pwd','pwd',3,'callback'),
		array('logincount','0'),
		array('loginip','get_client_ip',3,'function'),
		array('logintime','time',1,'function'),
	);
	public function pwd(){
		if (empty($_POST['pwd'])) {
		    return false;
		}else{
		    return md5(trim($_POST['pwd']));
		}
	}
}
?>