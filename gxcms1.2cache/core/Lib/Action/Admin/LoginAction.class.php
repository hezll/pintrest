<?php
/**
 * @name    后台登陆模块
 * @package GXCMS.Administrator
 */
class LoginAction extends CmsAction{
    //登陆界面
    public function index(){
    	//检测 right enter
        if(empty($_SESSION['right_enter'])) {
        echo '<script>top.location.href="./"</script>';exit;
        }
		if ($_SESSION[C('USER_AUTH_KEY')]){
			redirect(C('cms_admin').'?s=Admin/Index');
		}
		$this->display('./views/admin/login.html');
    }	
	//登陆检测_前置
	public function _before_check(){
		$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Login');
	    if (empty($_POST['login_name'])) {
			$this->error('管理员帐号必须填写！');
		}
		if (empty($_POST['login_pwd'])) {
			$this->error('管理员密码必须填写！');
		}		
	}	
	//登陆检测
    public function check(){
        $where = array();
		$where['user'] = $_POST['login_name'];
		$rs  = D("Admin.Master");
		$list= $rs->where($where)->find();
        //使用用户名、密码和状态的方式进行认证
        if (NULL == $list) {
            $this->error('管理员帐号不存在！');
        }
		if ($list['pwd'] != md5(trim($_POST['login_pwd']))) {
			$this->error('用户密码错误,请重新输入！');
		}
		// 缓存访问权限
		$_SESSION[C('USER_AUTH_KEY')] = $list['id'];
		$_SESSION['usertype']         = $list['usertype'];
		$_SESSION['user']             = $list['user'];
		
		//保存登陆信息
		$data = array();
		$data['id']         = $list['id'];
		$data['logincount'] = array('exp','logincount+1');
		$data['loginip']    = get_client_ip();
		$data['logintime']  = time();
		$rs->save($data);
		redirect(C('cms_admin').'?s=Admin/Index');
    }	
	// 登出
    public function logout(){
        if (isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION);
			session_destroy();
        }
		redirect(C('cms_admin').'?s=Admin/Login');
    }		
}
?>