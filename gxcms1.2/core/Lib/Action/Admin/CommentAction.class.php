<?php
/**
 * @name    用户评论管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class CommentAction extends AdminAction{
     private $CommDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->CommDB =D('Admin.Comment');
    }
	// 用户评论管理
    public function show(){
		$request['wd']     = urldecode(trim($_REQUEST['wd']));
		$request['status'] = intval($_GET['status']);
		if (!empty($request['wd'])) {
			$where[C('db_prefix').'comment.ip']      = array('like','%'.$request["wd"].'%');
			$where[C('db_prefix').'comment.content'] = array('like','%'.$request["wd"].'%');
			$where[C('db_prefix').'user.username']   = array('like','%'.$request["wd"].'%');
			$where[C('db_prefix').'video.title']     = array('like','%'.$request["wd"].'%');
			$where['_logic']                         = 'or';
	
		}
		if ($request['status']) {
			$where[C('db_prefix').'comment.status'] = 0;
		}
		$join  = C('db_prefix').'user on '.C('db_prefix').'comment.uid = '.C('db_prefix').'user.id';
		//查询影片名称
		$join_video= C('db_prefix').'video on '.C('db_prefix').'comment.did = '.C('db_prefix').'video.id';
		$field     = C('db_prefix').'comment.*,'.C('db_prefix').'user.id as userid,'.C('db_prefix').'user.username,'.C('db_prefix').'video.title';
		$order     = C('db_prefix').'comment.id desc';

		$cm_count  = $this->CommDB->field($field)->where($where)->join($join)->join($join_video)->count();
		$cm_page   = !empty($_GET['p'])?intval($_GET['p']):1;
		$cm_page   = get_cms_page_max($cm_count,C('web_admin_pagenum'),$cm_page);
		$cm_url    = U('Admin-Comment/Show',array('status'=>$request['status'],'wd'=>urlencode($request['wd']),'p'=>''),false,false);
		$listpages = get_cms_page($cm_count,C('web_admin_pagenum'),$cm_page,$cm_url,'条评论');
		$list_comment = $this->CommDB->field($field)->where($where)->join($join)->join($join_video)->order($order)->limit(C('web_admin_pagenum'))->page($cm_page)->select();
		//
		$_SESSION['comment_reurl'] = $cm_url.$cm_page;
		$this->assign($listpages);
		$this->assign('wd',$request['wd']);
		$this->assign('list_comment',$list_comment);
        $this->display('./views/admin/comment_show.html');
    }
	// 用户评论编辑
    public function add(){
		$where['id'] = $_GET['id'];
		$array = $this->CommDB->where($where)->find();
		$this->assign($array);	
        $this->display('./views/admin/comment_add.html');
    }
	// 更新用户评论
	public function update(){
		if(empty($_POST['status'])){
			$_POST['status'] = 0;
		}	
		if ($this->CommDB->create()) {
			if (false !== $this->CommDB->save()) {
			    $this->assign("jumpUrl",$_SESSION['comment_reurl']);
				$this->success('更新评论信息成功！');
			}else{
				$this->error("更新评论信息失败！");
			}
		}else{
			$this->error($this->CommDB->getError());
		}
	}
	// 删除评论BY-ID
    public function del(){
		$where['id'] = $_GET['id'];
		$this->CommDB->where($where)->delete();
		redirect($_SESSION['comment_reurl']);
    }
	// 删除评论All
    public function delall($uid){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的评论!');
		}	
		$where['id'] = array('in',implode(',',$_POST['ids']));
		$this->CommDB->where($where)->delete();
		redirect($_SERVER['HTTP_REFERER']);
    }	
	// 清空评论
    public function delclear(){
		$this->CommDB->where('id > 0')->delete();
		$this->success('所有用户评论信息已经清空！');
    }		
	// 隐藏与显示评论
    public function status(){
		$where['id'] = $_GET['id'];
		if(intval($_GET['sid'])){
			$this->CommDB->where($where)->setField('status',1);
		}else{
			$this->CommDB->where($where)->setField('status',0);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
    
   // 批量隐藏与显示评论
    public function statusall(){
       if(empty($_POST['ids'])){
			$this->error('请选择需要审核的评论!');
		}
		$where['id'] = array('in',implode(',',$_POST['ids']));
		if(intval($_GET['sid'])){
			$this->CommDB->where($where)->setField('status',1);
		}else{
			$this->CommDB->where($where)->setField('status',0);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
	// 用户评论Ajax
    public function showid(){
		$where['id'] = $_GET['id'];
		$array = $this->CommDB->where($where)->find();
		$this->assign($array);
        $this->display('./views/admin/comment_showid.html');
    }							
}
?>