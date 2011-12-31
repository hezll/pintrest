<?php
/**
 * @name    专题管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class SpecialAction extends AdminAction{
     private $SpecDB;	
     private $VideoDB;
	 private $InfoDB;
	 public function _initialize(){
	 	parent::_initialize();
	 	$this->SpecDB  =D('Admin.Special');
		$this->VideoDB =D('Admin.Video');
		$this->InfoDB  =D('Admin.Info');
    }
	// 专题列表		
    public function show(){
		$status = $_REQUEST['status'];
		if ($status) {
			$where['status'] = $status;
		}	
		$special['type']  = !empty($_GET['type'])?$_GET['type']:C('web_admin_ordertype');
		$special['order'] = !empty($_GET['order'])?$_GET['order']:'desc';
		$order = $special['type'].' '.$special['order'];	
		//分页开始
		$special_count = $this->SpecDB->where($where)->count('id');
		$special_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$special_page  = get_cms_page_max($special_count,C('web_admin_pagenum'),$special_page);
		$special_url   = U('Admin-Special/Show',array('status'=>$status,'type'=>$video['type'],'order'=>$video['order'],'p'=>''),false,false);
		$listpages     = get_cms_page($special_count,C('web_admin_pagenum'),$special_page,$special_url,'篇专辑');
		$list = $this->SpecDB->where($where)->order($order)->limit(C('web_admin_pagenum'))->page($special_page)->select();
		$_SESSION['special_reurl'] = $special_url.$special_page;
		foreach($list as $key=>$val){
			$list[$key]['countvideo'] = !empty($val['mids'])?count(explode(',',$val['mids'])):0;
			$list[$key]['countinfo']  = !empty($val['aids'])?count(explode(',',$val['aids'])):0;
			$list[$key]['specialurl'] = get_read_url('special',$list[$key]['id']);
		}		
		$this->assign($listpages);
		$this->assign('order',$order);
		$this->assign('list_special',$list);
		$this->display('views/admin/special_show.html');
    }
	// 添加专题与编辑专题	
    public function add(){
		$where['id'] = $_GET['id'];
		if ($where['id']) {
			$array = $this->SpecDB->where($where)->find();
			if (C('web_admin_edittime')){
			$array['checktime']= 'checked';
			}
			$array['tpltitle'] = '编辑';
		}else{
			$array['addtime']  = time();
			$array['checktime']= 'checked';
			$array['tpltitle'] = '添加';
		}
		$this->assign($array);
		$this->display('./views/admin/special_add.html');
    }
	// 新增专题保存到数据库
	public function insert(){
		if($this->SpecDB->create()){
			$id = $this->SpecDB->add();
			if( false!== $id){
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Special/Add');
			}else{
				$this->error('专题添加失败!');
			}
		}else{
		    $this->error($this->SpecDB->getError());
		}
	}
	// 新增专题保存到数据库-后置操作
	public function _after_insert(){
		$this->success('专题添加成功,继续添加新专题！');
	}
	// 更新专题信息
	public function update(){
		if ($this->SpecDB->create()) {
			if (false!==$this->SpecDB->save()) {
			    $this->assign("jumpUrl",$_SESSION['special_reurl']);
				$this->success('编辑专题信息成功');
			}else{
				$this->error("编辑专题信息失败!");
			}
		}else{
			$this->error($this->SpecDB->getError());
		}
	}
	// 隐藏与显示专题
    public function status(){
		$where['id'] = $_GET['id'];
		if(intval($_GET['sid'])){
			$this->SpecDB->where($where)->setField('status',1);
		}else{
			$this->SpecDB->where($where)->setField('status',0);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
	// 删除专题
    public function del(){
		$this->delfile($_GET['id']);
		redirect($_SERVER['HTTP_REFERER']);
    }	
	// 删除专题all
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的专题!');
		}	
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->delfile($val);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
	// 删除静态文件与图片
    public function delfile($id){
		$where['id'] = $id;
		$this->SpecDB->where($where)->delete();
    }
	// 专题收录影视查看
    public function showvideo(){
		$id  = intval($_GET['id']);
		$add = intval($_GET['add']);
		$key = intval($_GET['key']);
		$mids = $this->SpecDB->where('id = '.$id)->getField('mids');
		if ($add == 1) {//+
			if ($mids) {
				$mids .=','.$key;
			}else{
				$mids = $key;
			}
			$mids         = array_unique(explode(',',$mids));
			$data['mids'] = implode(',',$mids);;
			$this->SpecDB->where('id = '.$id)->save($data);
		}elseif ($add == 2) {//-
			$oldmids      = explode(',',$mids);
			$mids         = array_diff($oldmids,array('0'=>$key));
			$data['mids'] = implode(',',$mids);
			$this->SpecDB->where('id = '.$id)->save($data);
		}
		$where['id'] = array('in',$mids);
		$list = $this->VideoDB->field('id,cid,title,intro,actor')->where($where)->order('id desc')->select();	
		$this->assign($array);
		$this->assign('list_video',$list);
		$this->display('views/admin/special_mids.html');
    }	
	// 专题收录文章查看
    public function showinfo(){
		$id  = intval($_GET['id']);
		$add = intval($_GET['add']);
		$key = intval($_GET['key']);
		$aids = $this->SpecDB->where('id = '.$id)->getField('aids');
		if ($add == 1) {//+
			if ($aids) {
				$aids .=','.$key;
			}else{
				$aids = $key;
			}
			$aids         = array_unique(explode(',',$aids));
			$data['aids'] = implode(',',$aids);;
			$this->SpecDB->where('id = '.$id)->save($data);
		}elseif ($add == 2) {//-
			$oldaids      = explode(',',$aids);
			$aids         = array_diff($oldaids,array('0'=>$key));
			$data['aids'] = implode(',',$aids);
			$this->SpecDB->where('id = '.$id)->save($data);
		}
		$where['id'] = array('in',$aids);
		$list = $this->InfoDB->field('id,cid,title')->where($where)->order('id desc')->select();	
		$this->assign($array);
		$this->assign('list_info',$list);
		$this->display('views/admin/special_aids.html');
    }						
}
?>