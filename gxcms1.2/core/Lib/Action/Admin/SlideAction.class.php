<?php
 /**
 * @name    首页幻灯管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class SlideAction extends AdminAction{	
     private $SlideDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->SlideDB =D('Admin.Slide');
    }
    
	// 列表
    public function show(){
		$list = $this->SlideDB->where($where)->order('oid asc')->select();
		foreach($list as $key=>$val){
			$list[$key]['picurl'] = get_img_url($list[$key]['picurl']);	
		}			
		$this->assign('list_slide',$list);
		$this->display('./views/admin/slide_show.html');
    }
	// 添加与编辑
    public function add(){
		$id = intval($_GET['id']);
		if ($id>0) {
            $where['id'] = $id;
			$list = $this->SlideDB->where($where)->find();
			$list['tpltitle']= '编辑';
		}else{
		    $list['oid']     = $this->SlideDB->max('oid')+1;
			$list['status']  = 1;
			$list['tpltitle']= '添加';
		}
		$this->assign($list);
		$this->display('./views/admin/slide_add.html');
    }
	// 写入数据
	public function insert(){
		if ($this->SlideDB->create()) {
			if ( false !== $this->SlideDB->add() ) {
				redirect(C('cms_admin').'?s=Admin/Slide/Show');
				//$this->success('添加幻灯广告成功！');
			}else{
				$this->error('添加幻灯广告失败');
			}
		}else{
		    $this->error($this->SlideDB->getError());
		}		
	}	
	// 更新数据
	public function update(){
		if ($this->SlideDB->create()) {
			$list = $this->SlideDB->save();
			if ($list !== false) {
			    redirect(C('cms_admin').'?s=Admin/Slide/Show');
			}else{
				$this->error("幻灯更新失败!");
			}
		}else{
			$this->error($this->SlideDB->getError());
		}
	}
	// 隐藏与显示幻灯
    public function status(){
		$where['id'] = $_GET['id'];
		if (intval($_GET['sid'])) {
			$this->SlideDB->where($where)->setField('status',1);
		}else{
			$this->SlideDB->where($where)->setField('status',0);
		}
		$this->redirect('Admin-Slide/Show');
    }
	// 删除数据
    public function del(){
		$where['id'] = $_GET['id'];
		$this->SlideDB->where($where)->delete();
		$this->redirect('Admin-Slide/Show');
    }								
}
?>