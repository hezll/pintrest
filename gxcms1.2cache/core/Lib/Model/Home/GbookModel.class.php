<?php 
import('AdvModel');
class GbookModel extends AdvModel {
	protected $_validate=array(
		//array('vcode','check_vcode','您填写的验证码不正确！',1,'callback'),
		//array('username','check_login','您输入的用户名或密码错误！',2,'callback'),		
		array('content','require','您没有填写留言内容！',1),
		array('content','check_reinput','您已经留言过了,请晚点再来!',1,'callback'),
	);
	protected $_auto=array(
		array('uid','get_userid',1,'callback'),
		array('ip','get_client_ip',1,'function'),
		array('addtime','time',1,'function'),
		array('content','hh_content',1,'callback'),
	);	
	//检测验证码是否正确
	public function check_vcode(){
		if($_SESSION['verify'] != md5($_POST['vcode'])){
			return false;
		}
	}
	//检测指定时间内重复留言
	public function check_reinput(){
		$cookie = $_COOKIE['gbook-'.intval($_POST['errid'])];
		if(isset($cookie)){
			return false;
		}
	}
	//自动填充
	public function get_userid(){
		$userid = intval($_COOKIE['gx_userid']);
		if ($userid) {
			return $userid;
		}
		if (C('gx_userid')){
			return C('gx_userid');
		}
		return 1;
	}	
	//过滤脏话	
	public function hh_content($str){
		$array = explode('|',C('user_replace'));
		return str_replace($array,'***',get_replace_nb(remove_xss($str)));
	}		
}
?>