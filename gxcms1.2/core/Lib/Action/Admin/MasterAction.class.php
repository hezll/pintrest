<?php
 /**
 * @name    系统后台用户管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class MasterAction extends AdminAction{
     private $MasterDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->MasterDB =D('Admin.Master');
    }
	// 列表
    public function show(){
		$arrlist = $this->MasterDB->order('id desc')->select();
		$this->assign('list_master',$arrlist);
        $this->display('./views/admin/master_show.html');
    }
	// 添加与编辑
    public function add(){
		$where['id'] = $_GET['id'];
		if ($where['id']) {
			$arrlist = $this->MasterDB->where($where)->find();
			$arrtype = explode(',',$arrlist['usertype']);
			$arrlist['tpltitle'] = '编辑';
		}else{
			$arrlist['tpltitle'] = '添加';
		}
		$this->assign($arrlist);
		$this->assign('usertype',$arrtype);		
        $this->display('./views/admin/master_add.html');
    }
	// 添加数据
	public function insert(){
		if ($this->MasterDB->create()) {
			if (false!==$this->MasterDB->add()) {
			    $this->assign("jumpUrl",C('cms_admin').'?s=Admin/Master/Show');
				$this->success('创建系统用户成功！');
			}else{
				$this->error('创建系统用户错误');
			}
		}else{
		    $this->error($this->MasterDB->getError());
		}		
	}	
	//添加数据 前置操作 处理权限入库
	public function _before_insert(){
		$arr = $_POST['usertype'];
		for($i=0; $i<19; $i++){
			if( $arr[$i] ){
				$arrinsert[$i] = 1;
			}else{
				$arrinsert[$i] = 0;
			}
		}
		$_POST['usertype'] = implode(',',$arrinsert);
	}
	// 更新
	public function update(){
	    $this->_before_insert();
		if ($this->MasterDB->create()) {
			$arr = $this->MasterDB->save();
			if( $arr!==false ){
			    $this->assign("jumpUrl",C('cms_admin').'?s=Admin/Master/Show');
				$this->success('更新系统用户资料成功！');
			}else{
				$this->error('更新系统用户资料失败!');
			}
		}else{
			$this->error($this->MasterDB->getError());
		}
	}
	// 删除
    public function del(){
		if($_GET['id'] == $_SESSION[C('USER_AUTH_KEY')]){
			$this->error('对不起,不能删除自己当前所使用的帐号!');
		}
		$where['id'] = $_GET['id'];
		$this->MasterDB->where($where)->delete();
		$this->success('删除后台用户成功!');
    }
	// 批量删除
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的用户!');
		}
		if (in_array($_SESSION[C('USER_AUTH_KEY')],$_POST['ids'])){
			$this->error('对不起,请取消选择当前正在所使用的帐号!');
		}
		$where['id'] = array('in',implode(',',$_POST['ids']));
		$this->MasterDB->where($where)->delete();		
		$this->success('批量删除后台用户成功！');
    }					
}
?>