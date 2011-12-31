<?php
import('AdvModel');
class CommentModel extends AdvModel {
	protected $_validate=array(
		//array('vcode','check_vcode','您填写的验证码不正确！',1,'callback'),
		array('did','number','您没有指定评论ID！',1),
		array('mid','require','您没有指定评论模型！',1),
		array('content','require','您没有填写评论内容！',1),
		array('content','check_reinput','您已经评论过了!',1,'callback'),
		//array('username','check_login','您输入的用户名或密码错误！',2,'callback'),
	);
	protected $_auto=array(
		array('content','hh_content',1,'callback'),
		array('uid','get_userid',1,'callback'),
		array('mid','intval',1,'function'),
		array('did','intval',1,'function'),
		array('ip','get_client_ip',1,'function'),
		array('addtime','time',1,'function'),
	);
	//检测验证码是否正确
	public function check_vcode(){
		if($_SESSION['verify'] != md5($_POST['vcode'])){
			return false;
		}
	}
	//检测指定时间内重复评论
	public function check_reinput(){
		$cookie = $_COOKIE['comment-'.intval($_POST['mid']).'-'.intval($_POST['did'])];
		if(isset($cookie)){
			return false;
		}
	}
	//登陆验证
	public function check_login(){
		$userid = intval($_COOKIE['gx_userid']);
		if ($userid) {
			return true;
		}else{
			$rs = D('Home.User');
			$userid = $rs->check_login();
			if ($userid) {
				C('gx_userid',$userid);
				return true;
			}else{
				return false;
			}
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