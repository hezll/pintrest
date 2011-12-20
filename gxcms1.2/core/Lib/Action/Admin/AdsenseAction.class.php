<?php
/**
 * @name    广告管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class AdsenseAction extends AdminAction{
	 private $AdsDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->AdsDB =D('Admin.Adsense');
    }
	// 删除广告
    public function del(){
		$where['id'] = $_GET['id'];
		$array = $this->AdsDB->field('title')->where($where)->find();
	    $this->AdsDB->where($where)->delete();
		@unlink('./'.C('web_adsensepath').'/'.$array['title'].'.js');
		$this->success('删除广告位成功！');
    }
	// 批量删除
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的广告!');
		}	
		$array = $_POST;
		foreach($array['ids'] as $key=>$value){
			@unlink('./'.C('web_adsensepath').'/'.$array['titles'][$key].'.js');
			$this->AdsDB->where('id = '.intval($value))->delete();
		}
		$this->success('批量删除广告位成功！');
    }
	// 广告列表
    public function show(){
		$list = $this->AdsDB->order('id desc')->select();
		$this->assign('list',$list);
        $this->display('./views/admin/adsense.html');
    }		
	// 预览广告
    public function add(){
		$id = $_GET['id'];
		if ($id) {
			$list = $this->AdsDB->field('title')->where('id='.$id)->find();
			echo(get_cms_ads($list['title']));
		}
    }
	// 添加数据
	public function insert(){
		if ($this->AdsDB->create()) {
			if (false !== $this->AdsDB->add()) {
			    $this->assign("jumpUrl",C('cms_admin').'?s=Admin/Adsense/Show');
			}else{
				$this->error('添加广告位出错!');
			}
		}else{
		    $this->error($this->AdsDB->getError());
		}
	}
	//后置操作
	public function _after_insert(){
		$array = $_POST;
		write_file('./'.C('web_adsensepath').'/'.trim($array['title']).'.js',t2js(stripslashes(trim($array['content']))));
		$this->success('添加广告位成功！');
	}
	// 更新数据
	public function update(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要更新的广告!');
		}	
		$array = $_POST;
		foreach($array['ids'] as $key=>$value){
			$data['title'] = trim($array['titles'][$key]);
			$list = $this->AdsDB->field('id,title')->where($data)->find();
			if($list){
				if($value != $list['id']){
					$this->error('广告标识重复,请核查并重新填写!');
				}
			}
			//删除原广告代码
			$title = $this->AdsDB->where('id='.$value)->getField('title');
			@unlink('./'.C('web_adsensepath').'/'.$title.'.js');
			//
			$data['content'] = stripslashes(trim($array['contents'][$key]));
			$this->AdsDB->where('id = '.$value)->save($data);
			write_file('./'.C('web_adsensepath').'/'.$data['title'].'.js',t2js($data['content']));
		}
		$this->success('批量修改广告位成功！');
	}				
}
?>