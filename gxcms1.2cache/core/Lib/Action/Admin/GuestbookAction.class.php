<?php
 /**
 * @name    用户留言管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class GuestbookAction extends AdminAction{	
     private $GbookDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->GbookDB =D('Admin.Gbook');
    }
	// 用户留言管理
    public function show(){
		$request['eid']    = $_REQUEST['eid'];
		$request['wd']     = urldecode(trim($_REQUEST['wd']));
		$request['status'] = intval($_GET['status']);
		if ($request['eid']) {
			$where[C('db_prefix').'gbook.errid'] = array('gt',0);
			$request['showname'] = '报错';
		}else{
			$where[C('db_prefix').'gbook.errid'] = array('eq',0);
			$request['showname'] = '留言';
		}
		if ($request['status']) {
			$where[C('db_prefix').'gbook.status'] = array('eq',0);
		}			
		if (!empty($request['wd'])) {
			$search[C('db_prefix').'gbook.ip']      = array('like','%'.$request['wd'].'%');
			$search[C('db_prefix').'gbook.content'] = array('like','%'.$request['wd'].'%');
			$search[C('db_prefix').'user.username'] = array('like','%'.$request['wd'].'%');
			$search['_logic'] = 'or';
			$where['_complex'] = $search;
		}
		$join  = C('db_prefix').'user on '.C('db_prefix').'gbook.uid = '.C('db_prefix').'user.id';
		$field = C('db_prefix').'gbook.*,'.C('db_prefix').'user.id as userid,'.C('db_prefix').'user.username';
		$order = C('db_prefix').'gbook.id desc';

		$gb_count  = $this->GbookDB->field($field)->where($where)->join($join)->count();
		$gb_page   = !empty($_GET['p'])?intval($_GET['p']):1;
		$gb_page   = get_cms_page_max($gb_count,C('web_admin_pagenum'),$gb_page);		
		$gb_url    = U('Admin-Guestbook/Show',array('status'=>$request['status'],'eid'=>$request['eid'],'wd'=>urlencode($request['wd']),'p'=>''),false,false);
		$listpages = get_cms_page($gb_count,C('web_admin_pagenum'),$gb_page,$gb_url,'条留言');
		$list_gbook= $this->GbookDB->field($field)->where($where)->join($join)->order($order)->limit(C('web_admin_pagenum'))->page($gb_page)->select();
		//
		$_SESSION['guestbook_reurl'] = $gb_url.$gb_page;
		$this->assign($listpages);
		$this->assign($request);
		$this->assign('list_gbook',$list_gbook);
        $this->display('./views/admin/guestbook_show.html');
    }
	// 用户留言编辑
    public function add(){
		$where['id'] = $_GET['id'];
		$array = $this->GbookDB->where($where)->find();
		$this->assign('reid',$_GET['reid']);
		$this->assign($array);	
        $this->display('./views/admin/guestbook_add.html');
    }
	// 更新用户留言
	public function update(){
		if(empty($_POST['status'])){
			$_POST['status'] = 0;
		}
		if ($this->GbookDB->create()) {
			if (false !== $this->GbookDB->save()) {
			    $this->assign("jumpUrl",$_SESSION['guestbook_reurl']);
				$this->success('更新留言信息成功！');
			}else{
				$this->error("更新留言信息失败！");
			}
		}else{
			$this->error($this->GbookDB->getError());
		}
	}
	// 删除留言BY-ID
    public function del(){
		$where['id'] = $_GET['id'];
		$this->GbookDB->where($where)->delete();
		//$_SERVER['HTTP_REFERER']
		redirect($_SESSION['guestbook_reurl']);
    }
	// 删除留言All
    public function delall($uid){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的内容!');
		}	
		$where['id'] = array('in',implode(',',$_POST['ids']));
		$this->GbookDB->where($where)->delete();
		redirect($_SERVER['HTTP_REFERER']);
    }	
	// 清空留言
    public function delclear(){
		if ($_REQUEST['eid']) {		
			$this->GbookDB->where('errid > 0')->delete();
		}else{
			$this->GbookDB->where('errid = 0')->delete();
		}
		$this->success('所有用户留言或报错信息已经清空！');
    }
	// 隐藏与显示留言
    public function status(){
		$where['id'] = $_GET['id'];
		if(intval($_GET['sid'])){
			$this->GbookDB->where($where)->setField('status',1);
		}else{
			$this->GbookDB->where($where)->setField('status',0);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
   // 批量隐藏与显示留言
    public function statusall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要审核的留言!');
		}
		$where['id'] = array('in',implode(',',$_POST['ids']));
		if(intval($_GET['sid'])){
			$this->GbookDB->where($where)->setField('status',1);
		}else{
			$this->GbookDB->where($where)->setField('status',0);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
	// 用户留言Ajax
    public function showid(){
		$where['id'] = $_GET['id'];
		$array = $this->GbookDB->where($where)->find();
		$this->assign($array);	
        $this->display('./views/admin/guestbook_showid.html');
    }							
}
?>