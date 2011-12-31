<?php
 /**
 * @name    友情链接管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class LinkAction extends AdminAction{
	 private $LinkDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->LinkDB =D('Admin.Link');
    }	
	// 删除数据
    public function del(){
		$where['id'] = $_GET['id'];
		$this->LinkDB->where($where)->delete();
		$this->success('删除友情链接成功！');
    }	
	// 批量删除
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的友情链接!');
		}
		$where['id'] = array('in',implode(',',$_POST['ids']));
		$this->LinkDB->where($where)->delete();
		$this->success('批量删除友情链接成功！');
    }	
	// 列表
    public function show(){
		$list = $this->LinkDB->order('oid asc')->select();
		$oid  = $this->LinkDB->max('oid')+1;
		$this->assign('list',$list);
		$this->assign('oid',$oid);
        $this->display('./views/admin/link.html');
    }	
	// 添加数据
	public function insert(){
		if ($this->LinkDB->create()) {
			if (false !== $this->LinkDB->add()) {
			    $this->assign("jumpUrl",C('cms_admin').'?s=Admin/Link/Show');
				$this->success('添加友情链接成功！');
			}else{
				$this->error('添加友情链接失败!');
			}
		}else{
		    $this->error($this->LinkDB->getError());
		}		
	}
	// 更新数据
	public function update(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要更新的友情链接!');
		}	
		$array = $_POST;
		foreach($array['ids'] as $key=>$value){
			$id            = intval($value);
		    $data['type']  = $array['types'][$id];
			$data['title'] = $array['titles'][$id];
			$data['url']   = $array['urls'][$id];
			$data['logo']  = $array['logos'][$id];
			$data['oid']   = $array['oids'][$id];
			$this->LinkDB->where('id = '.$id)->save($data);
		}
		$this->success('批量修改友情链接更新成功！');
	}				
}
?>