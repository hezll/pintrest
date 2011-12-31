<?php
/**
 * @name    栏目管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class ChannelAction extends AdminAction{
     private $ChannelDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->ChannelDB =D('Admin.Channel');
    }	
	// 删除数据
    public function del(){
		$where['id'] = $_GET['id'];
		if (!get_channel_son($_GET['id'])) {
			$this->error("请先删除本类下面的子栏目！");
		}
		$this->ChannelDB->where($where)->delete();
		$mid = get_channel_name($id,'mid');
		$this->deldata($mid,$id);
		$this->create_channel();
		$this->success('成功删除该栏目分类与本类有关的内容！');
    }
	//删除对应的数据
	public function deldata($mid,$cid){
		if ($mid == 1) {
			$rs = M("Video");
			$rs->where('cid = '.$cid)->delete();
		}elseif($mid == 2){
			$rs = M("Info");
			$rs->where('cid = '.$cid)->delete();			
		}
	}
	// 批量删除数据
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的栏目!');
		}	
		$array = $_POST;
		foreach($array['ids'] as $value){
			$id  = intval($value);
			$mid = get_channel_name($id,'mid');
			if (!get_channel_son($id)) {
				$this->error("请先删除本类下面的子栏目！");
			}			
		    $this->ChannelDB->where('id = '.$id)->delete(); 
			$this->deldata($mid,$id);
		}
		$this->create_channel();
		$this->success('批量删除栏目成功！');
    }
	// 列表
    public function show(){
	    $cid = intval($_GET['id']);
		if ($cid) {
			$where['id'] = get_channel_sqlin($cid);
		}
		$list = $this->ChannelDB->where($where)->order('oid asc')->select();
		if ($list) {
			$this->assign('listtree',list_to_tree($list,'id','pid','son',0));
			$this->display('./views/admin/channel_show.html');
		}else{
		    $this->assign("jumpUrl",C('cms_admin').'?s=Admin/Channel/Add');
			$this->success('暂无栏目分类,请先添加！');		    
		}
    }
	// 添加与编辑
    public function add(){
		$cid = $_GET['id'];
		if ($cid>0) {
            $where['id'] = $cid;
			$list = $this->ChannelDB->where($where)->find();
			$list['tpltitle'] = '编辑';
		}else{
		    $list['id']       = 0;
		    $list['pid']      = $_GET['pid'];
			$list['mid']      = $_GET['mid'];
		    $list['oid']      = $this->ChannelDB->max('oid')+1;
			$list['ctpl']     = 'video_list';
			$list['status']   = 1;
			$list['tpltitle'] = '添加';
		}
		$this->assign($list);
		$this->assign('channel_tree',F('_gxcms/channeltree'));
		$this->assign('channel_info',F('_gxcms/channelinfo'));
		$this->display('./views/admin/channel_add.html');
    }
	//写入前置
	public function _before_insert(){
		if($_POST['mid'] == 9){
			$_POST['cfile'] = uniqid();
		}
	}	
	// 写入数据
	public function insert(){
		if ($this->ChannelDB->create()) {
			if ( false !== $this->ChannelDB->add() ) {
			    $this->create_channel();
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Channel/Show');
				$this->success('添加栏目分类成功！');
			}else{
				$this->error('添加栏目分类错误');
			}
		}else{
		    $this->error($this->ChannelDB->getError());
		}		
	}
	public function _before_update(){
		$where['cfile'] = trim($_POST['cfile']);
		$list = $this->ChannelDB->field('id,cfile')->where($where)->find();
		if(!empty($list)){
			if(intval($_POST['id']) != $list['id']){
				$this->error('栏目英文别名已经存在,请重新填写!');
			}
		}
	}	
	// 更新数据
	public function update(){
		if ($this->ChannelDB->create()) {
			$list = $this->ChannelDB->save();
			if ($list !== false) {
			    $this->create_channel();
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Channel/Show');
				$this->success('栏目分类更新成功！');
			}else{
				$this->error("栏目分类更新失败!");
			}
		}else{
			$this->error($this->ChannelDB->getError());
		}
	}
	// 批量更新数据
    public function updateall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要修改的栏目!');
		}	
		$array = $_POST;
		foreach($array['ids'] as $key=>$value){
		    $data['oid'] = intval($array['oid'][$value]);
			$this->ChannelDB->where('id = '.intval($value))->save($data);
		}
		$this->create_channel();
		$this->success('批量更新栏目信息更新成功！');
    }
	// 隐藏与显示栏目
    public function status(){
		$where['id'] = intval($_GET['id']);
		if (!get_channel_son($where['id'])) {
			$this->error("该栏目有子类,不可以隐藏!");
		}
		if (intval($_GET['sid'])) {
			$this->ChannelDB->where($where)->setField('status',1);
		}else{
			$this->ChannelDB->where($where)->setField('status',0);
		}	
		$this->create_channel();
		$this->redirect('Admin-Channel/Show');
    }								
}
?>