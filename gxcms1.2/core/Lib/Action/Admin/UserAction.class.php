<?php
/**
 * @name    用户管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class UserAction extends AdminAction{
	 private $UserDB;	
     private $UserVDB;
	 private $GbookDB;
	 private $CommDB;
	 public function _initialize(){
	 	parent::_initialize();
	 	$this->UserDB  =D('Admin.User');
		$this->UserVDB =D('Admin.Userview');
		$this->GbookDB =D('Admin.Gbook');
		$this->CommDB  =D('Admin.Comment');
    }
	// 用户管理
    public function show(){
		$keyword = trim($_REQUEST['keyword']);
		if ($keyword) {
			$where['username']     = array('like','%'.$keyword.'%');
			$where['binary email'] = array('like','%'.$keyword.'%');
			$where['_logic']       = 'or';
		}
	   
		$user_count = $this->UserDB->where($where)->count('id');
		$user_page  = !empty($_GET['p'])?$_GET['p']:1;
		$user_url   = U('Admin-User/Show',array('keyword'=>urlencode($keyword),'p'=>''),false,false);
		$listpages  = get_cms_page($user_count,C('web_admin_pagenum'),$user_page,$user_url,'位用户');
		//
		$list = $this->UserDB->where($where)->order('jointime desc')->limit(C('web_admin_pagenum'))->page($user_page)->select();
		if (empty($list)) {
			$this->error('没有查询到您所筛选的用户!');
		}
		$this->assign('keyword',$keyword);
		$this->assign($listpages);
		$this->assign('list_user',$list);
        $this->display('./views/admin/user_show.html');
    }
	// 用户添加与编辑表单
    public function add(){
		$user_id = $_GET['id'];
		if ($user_id>0) {
            $where['id'] = $user_id;
			$array = $this->UserDB->where($where)->find();
			$array['tpltitle'] = '编辑';
		}else{
			$array['id']      =0;
			$array['money']   =0;
			$array['duetime'] = time();
			$array['tpltitle']= '添加';
		}
		$this->assign($array);
        $this->display('./views/admin/user_add.html');
    }
	// 用户添加入库
	public function insert() {
		if($this->UserDB->create()) {
			if($this->UserDB->add()) {
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/User/Show');
    			$this->success('添加用户成功！');
            }else{
                $this->error('添加用户失败!');
            }
		}else{
			$this->error($this->UserDB->getError());
		}
	}
	public function _before_update(){
		$where['username'] = trim($_POST['username']);
		$where['email']    = trim($_POST['email']);
		$where['_logic']   = 'or';
		$list = $this->UserDB->field('id,username,email')->where($where)->find();
		if(!empty($list)){
			if(intval($_POST['id']) != $list['id']){
				$this->error('用户名或邮箱已经存在,请重新填写!');
			}
		}
	}	
	// 更新用户
	public function update(){
		if ($this->UserDB->create()) {
			if (false !== $this->UserDB->save()) {
			    $this->assign("jumpUrl",C('cms_admin').'?s=Admin/User/Show');
				$this->success('更新用户信息成功！');
			}else{
				$this->error("更新用户信息失败!");
			}
		}else{
			$this->error($this->UserDB->getError());
		}
	}
	// 删除用户
	public function del(){
		if ($_GET['id'] == 1){
			$this->error("系统默认用户,不能删除!");
		}
		$this->deluser(intval($_GET['id']));
		redirect($_SERVER['HTTP_REFERER']);
	}
	// 删除用户All
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的用户!');
		}
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->deluser(intval($val));
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
	// 删除用户资料等信息
    public function deluser($id){
		//用户中心
		$where['id'] = $id;
		$this->UserDB->where($where)->delete();
		//付费观看记录	
		$this->UserVDB->where(array('uid',$where['id']))->delete();
		unset($where);
		//留言
		$where['uid'] = $id;
		$this->GbookDB->where($where)->delete();
		unset($where);
		//评论
		$where['uid'] = $id;
		$this->CommDB->where($where)->delete();
    }	
	// 删除未登录用户
	public function delnum(){
		$where['lognum'] = array('lt',2);
		$where['id']     = array('gt',1);
		$this->UserDB->where($where)->delete();
		$this->success('不活跃用户删除成功！');
	}
	// 隐藏与显示用户
    public function status(){
		$where['id'] = $_GET['id'];
		if(intval($_GET['sid'])){
			$this->UserDB->where($where)->setField('status',1);
		}else{
			$this->UserDB->where($where)->setField('status',0);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }										
}
?>