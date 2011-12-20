<?php
/**
 * @name    用户观看记录管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class UserviewAction extends AdminAction{	
     private $UserVDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->UserVDB =D('Admin.Userview');
    }
	// 用户观看记录
    public function show(){
		$join  = C('db_prefix').'video on '.C('db_prefix').'userview.did = '.C('db_prefix').'video.id';
		$join2 = C('db_prefix').'user on '.C('db_prefix').'userview.uid = '.C('db_prefix').'user.id';
		$field = C('db_prefix').'userview.*,'.C('db_prefix').'video.id as videoid,'.C('db_prefix').'video.title,'.C('db_prefix').'user.username';
		$order = C('db_prefix').'video.id desc';
		//
		$user_count = $this->UserVDB->field($field)->join($join)->join($join2)->count();
		$user_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$totalPages = ceil($user_count/C('web_admin_pagenum'));
		if ($user_page > $totalPages){
			$user_page = $totalPages;
		}		
		$user_url  = U('Admin-Userview/Show',array('p'=>''),false,false);
		$listpages = get_cms_page($user_count,C('web_admin_pagenum'),$user_page,$user_url,'条记录');
		//
		$list_view = $this->UserVDB->field($field)->join($join)->join($join2)->order($order)->limit(C('web_admin_pagenum'))->page($user_page)->select();
		$this->assign($listpages);
		$this->assign('list_view',$list_view);
		$this->assign('list_channel_video',F('_gxcms/channelvideo'));
        $this->display('./views/admin/userview_show.html');
    }	
	// 删除观看记录BY-ID
    public function del(){
		$where['id'] = $_GET['id'];
		$this->UserVDB->where($where)->delete();
		redirect($_SERVER['HTTP_REFERER']);
    }
	// 删除观看记录All
    public function delall($uid){
		$where['id'] = array('in',implode(',',$_POST['ids']));
		$this->UserVDB->where($where)->delete();
		redirect($_SERVER['HTTP_REFERER']);
    }
	// 清空观看记录ALL
    public function delclear(){
		$this->UserVDB->where('id > 0')->delete();
		$this->success('所有观看记录已经清空！');
    }							
}
?>