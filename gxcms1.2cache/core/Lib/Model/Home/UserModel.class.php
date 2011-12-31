<?php 
import('AdvModel');
class UserModel extends AdvModel {
	protected $_validate = array(
		array('email','email','会员邮箱格式错误！',1,'',1),
		array('username','require','用户名称必须填写！',1,'',1),
		array('userpwd','require','用户密码必须填写！',1,'',1),
		array('reuserpwd','userpwd','确认密码不正确',1,'confirm','',3),
		array('vcode','check_vcode','验证码不正确,请重新输入',0,'callback','',1),
		array('username','','用户名称已经存在！',1,'unique',1,'',1),
		array('email','','Email帐号已经存在！',1,'unique',1,'',1),		
		array('agree','require','对不起,您没有接受注册协议！',1,'',1),
		array('question','require','请填写密保问题！',1,'',1),
		array('answer','require','请填写密保答案！',1,'',1),		
		array('qq','check_qq','QQ号码格式错误！',2,'callback',3),
		array('olduserpwd','check_oldpwd','原始密码出错！',2,'callback',2),
	);
	// 自动填充设置
	protected $_auto = array(
		array('username','trim',1,'function'),
		array('username','remove_xss',1,'function'),
		array('userpwd','check_pwd',3,'callback'),
		array('money','check_money',1,'callback'),
		array('joinip','get_client_ip',1,'function'),
		array('logip','get_client_ip',1,'function'),
		array('jointime','time',1,'function'),
		array('logtime','time',1,'function'),
		array('duetime','time',1,'function'),
		array('jointime','time',1,'function'),
		array('logtime','time',1,'function'),
		array('lognum',1),
	);
	public function check_vcode(){
		if ($_SESSION['verify'] == md5($_POST['vcode'])) {
			return true;
		}else{
			return false;
		}	
	}
	public function check_pwd(){
		if (empty($_POST['userpwd'])) {
		    return false;
		}else{
		    return md5(trim($_POST['userpwd']));
		}
	}
	public function check_qq($value){
		return preg_match("/^[1-9]\d{4,10}$/",$value) === 1;		
	}	
	public function check_money(){
		return intval(C('user_money_add'));
	}
	public function check_oldpwd(){
		$rs = M("User");
		$where['username'] = array('eq',trim($_POST['username']));
		$list = $rs->where($where)->find();
		if($list['userpwd'] == md5(trim($_POST['olduserpwd']))){
		    return true;
		}else{
		    return false;
		}
	}		
	//登陆检测
    public function check_login(){
		$username = trim($_POST['username']);
		$userpwd  = trim($_POST['userpwd']);
		$where['username'] = array('eq',$username);
		$where['email']    = array('eq',$username);
		$where['_logic']   = 'or';
		$rs = M("User");
		$list = $rs->where($where)->find();
        if (NULL == $list) {
            return NULL;
        }		
		if ($list['userpwd'] != md5($userpwd)) {
			return false;
		}
		if (!$list['status']) {
			return 0;
		}		
		//保存登陆信息
		$this->addcookie($list['username'],$list['userpwd'],$list['id']);	
		$data = array();
		$data['id']     = $list['id'];
		$data['lognum'] = array('exp','lognum+1');
		$data['logip']  = get_client_ip();
		$data['logtime']= time();
		$rs->save($data);
		return $list['id'];
		//return true;
    }
	// 写入cookie
    public function addcookie($username,$userpwd,$userid){
		setcookie("gx_username", $username, 0);
		setcookie("gx_userpwd", $userpwd, 0);
		setcookie("gx_userid", $userid, 0);	
    }
	// 清空cookie
    public function delcookie($username,$userpwd,$userid){
		setcookie("gx_username", '',time()-1);
		setcookie("gx_userpwd", '',time()-1);
		setcookie("gx_userid", '',time()-1);	
    }	
}
?>