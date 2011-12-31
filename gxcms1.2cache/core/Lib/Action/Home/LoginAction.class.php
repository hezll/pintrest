<?php
class LoginAction extends CmsAction{
	//显示用户名
    public function index(){
		$userid   = intval($_COOKIE['gx_userid']);
		$username = $_COOKIE['gx_username'];
		if($userid){
			echo '<a href="'.C('web_path').'index.php?s=user/show" target="_blank" class="username">'.htmlspecialchars($username).'</a> | <a href="'.C('web_path').'index.php?s=user/logout/re/true">退出</a>';
		}else{
			echo 'false';
		}
    }
	//登陆检测_前置
	public function _before_ajaxcheck(){
	    if (empty($_POST['username'])) {
			exit('请填写用户名称！');
		}
		if (empty($_POST['userpwd'])) {
			exit('请填写用户密码！');
		}
	}	
   public function ajaxcheck(){
		$rs = D('Home.User');
		$login = $rs->check_login();
		if ($login === NULL){
			exit('没有该用户的注册信息！');
		}
		if ($login === false){
			exit('用户密码错误，请重新输入！');
		}
		if ($login === 0){
			exit('该用户已被管理员锁定，如有疑问请联系管理员！');
		}
		echo('true');
    }	
}
?>