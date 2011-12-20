<?php
class UserAction extends HomeAction{
    public function checklogin(){
		$userid   = intval($_COOKIE['gx_userid']);
		$username = $_COOKIE['gx_username'];
		$userpwd  = $_COOKIE['gx_userpwd'];
		if ($userid) {
			$rs = M("User");
			$where['id'] = array('eq',$userid);
			$list = $rs->where($where)->find();
			if(($username == $list['username'])&&($userpwd == $list['userpwd'])){
				return $list;
			}
		}
		$this->assign("jumpUrl",'index.php?s=User/Login');
		$this->error('登陆超时或未登陆,请重新登陆!');
    }
    public function index(){
		$this->show();
    }
	//用户中心框架
    public function show(){
		$user = $this->checklogin();
		$this->assign($user);
		$this->display('./views/user/show.html');
    }
	// 独立登录界面
    public function login(){
        $this->display('./views/user/login.html');
    }
	// 用户注册界面
    public function reg(){
		if (!C('user_register')) {
			$this->assign("jumpUrl",'./');
			$this->error('对不起,暂未对外开放用户注册!');
		}
        $this->display('./views/user/reg.html');
    }
	// 注册信息写入数据库
	public function insert(){
		$rs = D("Home.User");
		if ($rs->create()) {
			$userid = $rs->add();
			if ($userid) {
				$username = remove_xss(trim($_POST['username']));
				$userpwd = md5(trim($_POST['userpwd']));
				$rs->addcookie($username,$userpwd,$userid);	
				$this->assign("jumpUrl",'index.php?s=User/Show');
    			$this->success('恭喜您,注册成功,马上进入用户中心！');
            }else{
                $this->error('注册失败,请重试!');
            }
		}else{
			$this->error($rs->getError());
		}
	}
	// 更新用户信息
	public function update(){
		$rs = D("Home.User");
		if ($rs->create()) {
			if (false !== $rs->save()) {
				$this->assign("jumpUrl",'index.php?s=User/Login');
				$this->success('用户信息更新成功,请重新登录！');
			}else{
				$this->error("用户信息更新失败!");
			}
		}else{
			$this->error($rs->getError());
		}
	}
	//登陆检测_前置
	public function _before_check(){
	    if (empty($_POST['username'])) {
			$this->error('请填写用户名称！');
		}
		if (empty($_POST['userpwd'])) {
			$this->error('请填写用户密码！');
		}
		if($_POST['vcode']){
			if ($_SESSION['verify'] != md5($_POST['vcode'])) {
				$this->error('验证码错误,请重新输入！');
			}
		}
	}
	//登陆验证
    public function check(){
		if($_POST['jumpurl'] && (strpos($_POST['jumpurl'],"index.php?s=User/")==false)){
			$backurl = htmlspecialchars(trim($_POST['jumpurl']));
		}else{
			$backurl = 'index.php?s=User/Show';
		}
		$rs = D('Home.User');
		$login = $rs->check_login();
		if ($login === NULL){
			$this->assign("jumpUrl",'index.php?s=User/Login');
			$this->error('没有该用户的注册信息，请重新输入或注册！');
		}
		if ($login === false){
			$this->assign("jumpUrl",'index.php?s=User/Login');
			$this->error('用户密码错误，请重新输入！');
		}
		if ($login === 0){
			$this->assign("waitSecond",5);
			$this->assign("jumpUrl",'index.php?s=User/Login');
			$this->error('该用户已被管理员锁定，如有疑问请联系管理员！');
		}
		redirect($backurl);
    }
	// 登出
    public function logout(){
		$re = $_REQUEST['re'];
		$userid = intval($_COOKIE['gx_userid']);
		if ($re && $userid) {
			$backurl = htmlspecialchars($_SERVER['HTTP_REFERER']);
		}else{
			$backurl = C('web_path').'index.php?s=User/Login';
		}
		$rs = D('Home.User');
		$rs->delcookie();
        redirect($backurl);
    }
	//重置密码
    public function repass(){
		$id = intval($_GET['id']);
		$this->assign('jumpUrl','index.php?s=User/Repass');
		if ($id == 1){
			$username = trim($_POST['username']);
			$rs = M("User");
			$where['username'] = array('eq',$username);
			$where['email']    = array('eq',$username);
			$where['_logic']   = 'or';
			$list = $rs->where($where)->find();
			if (empty($list)) {
				$this->error('没有找到相关用户,请重新输入！');
			}
			if (empty($list['answer'])) {
				$this->error('你没有填写密保答案,将不能使用该功能！');
			}
			$this->assign($list);
		}elseif ($id == 2){
			$rs = M("User");
			$where['id'] = intval($_POST['uid']);
			$list = $rs->where($where)->find();
			if($list['answer'] == trim($_POST['answer'])){
				if(trim($_POST['userpwd']) != trim($_POST['reuserpwd'])){
					$this->error('两次输入的密码不一样！');
				}
				$where['userpwd'] = md5(trim($_POST['userpwd'])); 
				$rs->save($where);
				$this->assign("waitSecond",120);
				$this->assign('jumpUrl','index.php?s=User/Login');
				$this->success('恭喜您,密码重置成功，请登录！');
			}else{
				$this->error('密保答案不正确,请重新输入！');
			}			
		}
		$this->assign('rid',$id);
		$this->display('./views/user/repass.html');
    }
	//积分转换
    public function shop(){
		$list = $this->checklogin();
		$this->assign($list);
		$this->display('./views/user/shop.html');
    }	
	//积分转换
    public function changepay(){
		$list = $this->checklogin();
		$this->assign($list);
		$this->display('./views/user/changepay.html');
    }
	//积分转换
    public function changeupdate(){
		$list = $this->checklogin();
		if ($list['money'] < intval(C('user_money_change'))) {
			$this->error('对不起你的积分不够,请联系管理员增加积分!');
		}else{
			$rs = D("Home.User");
			$data['id']      = $list['id'];
			$data['pay']     = 1;
			$data['duetime'] = array('exp',time()+2592000);
			$rs->save($data);
			$rs->setDec('money','id='.$list['id'],intval(C('user_money_change'))); 
			$this->assign('jumpUrl','index.php?s=User/Changepay');
        	$this->success('恭喜您,升级为包月会员成功！');
		}
    }		
	//用户资料
    public function read(){
		$list = $this->checklogin();
		$this->assign($list);
		$this->display('./views/user/read.html');
    }
	//用户资料
    public function edit(){
		$list = $this->checklogin();
		$this->assign($list);
		$this->display('./views/user/edit.html');
    }
	//用户观看记录
    public function views(){
		$arr   = $this->checklogin();
		$uid   = $arr['id'];
		$where[C('db_prefix').'userview.uid'] = $uid;
		$join  = C('db_prefix').'video on '.C('db_prefix').'userview.did = '.C('db_prefix').'video.id';
		$field = C('db_prefix').'userview.*,'.C('db_prefix').'video.id as videoid,'.C('db_prefix').'video.title,'.C('db_prefix').'video.cid';
		$order = C('db_prefix').'userview.viewtime desc';
		//
		$rs = M('Userview');
		$user_count = $rs->field($field)->where($where)->join($join)->count();
		$user_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$user_page  = get_cms_page_max($user_count,20,$user_page);
		$user_url   = U('User/Views',array('p'=>''),false,false);
		$listpages  = get_cms_page($user_count,C('web_admin_pagenum'),$user_page,$user_url,'条记录',false);
		//
		$list_view = $rs->field($field)->where($where)->join($join)->order($order)->limit(C('web_admin_pagenum'))->page($user_page)->select();
		foreach($list_view as $key=>$val){
			$list_view[$key]['floor'] = $user_count-(($user_page-1) * C('web_admin_pagenum') + $key);
		}
		$this->assign('pages',$listpages['listpages']);
		$this->assign('list_view',$list_view);
		$this->assign('count_view',$user_count);
		$this->display('./views/user/views.html');
    }
	//用户留言
    public function guestbook(){
		$arr = $this->checklogin();
		$where['uid']   = $arr['id'];
		$where['errid'] = array('eq',0);
		$rs = M('Gbook');
		$gbook_count = $rs->where($where)->count('id');
		$gbook_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$gbook_url   = U('User/Guestbook',array('p'=>''),false,false);
		$listpages   = get_cms_page($gbook_count,C('user_page_gb'),$gbook_page,$gbook_url,'篇留言',false);
		$listgbook   = $rs->where($where)->order('addtime desc,id desc')->limit(C('user_page_gb'))->page($gbook_page)->select();
		$this->assign('pages',$listpages['listpages']);
		$this->assign('list_guestbook',$listgbook);	
		$this->assign('count_guestbook',$gbook_count);		
		$this->display('./views/user/guestbook.html');
    }	
	//用户评论
    public function comment(){
		$arr = $this->checklogin();
		$where['uid'] = $arr['id'];
		$rs = M('Comment');
		$comment_count = $rs->where($where)->count('id');
		$comment_page  = !empty($_GET['p'])?$_GET['p']:1;$comment_page = intval($comment_page);
		$comment_url   = U('User/Comment',array('p'=>''),false,false);
		$listpages     = get_cms_page($comment_count,C('user_page_cm'),$comment_page,$comment_url,'条评论',false);
		$listcomment   = $rs->where($where)->order('addtime desc,id desc')->limit(C('user_page_cm'))->page($comment_page)->select();
		$this->assign('pages',$listpages['listpages']);
		$this->assign('list_comment',$listcomment);
		$this->assign('count_comment',$comment_count);
		$this->display('./views/user/comment.html');
    }										
}
?>