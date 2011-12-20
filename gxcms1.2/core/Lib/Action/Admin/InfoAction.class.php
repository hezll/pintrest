<?php
 /**
 * @name    文章资讯管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class InfoAction extends AdminAction{
     private $InfoDB;
	 public function _initialize(){
	 	parent::_initialize();
		$this->InfoDB =D('Admin.Info');
    }
	// 新闻列表	
    public function show(){
		$where   = array();
		$cid     = $_REQUEST['cid'];
		$keyword = urldecode(trim($_REQUEST['keyword']));
		if ($cid) {
			if(get_channel_son($cid)){
				$where['cid'] = $cid;
			}else{
				$where['cid'] = get_channel_sqlin($cid);
			}
		}
		if ($keyword) {
			$search['title']  = array('like','%'.$keyword.'%');
			$map              = $search;
			$map['_logic']    = 'or';
			$where['_complex']= $map;
		}
		$info['type']  = !empty($_GET['type'])?$_GET['type']:C('web_admin_ordertype');
		$info['order'] = !empty($_GET['order'])?$_GET['order']:'desc';
		$order         = $info["type"].' '.$info['order'];
		//分页开始
		$news_count = $this->InfoDB->where($where)->count('id');
		$news_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$news_page  = get_cms_page_max($news_count,C('web_admin_pagenum'),$news_page);		
		$news_url   = U('Admin-Info/Show',array('cid'=>$cid,'type'=>$info['type'],'order'=>$info['order'],'keyword'=>urlencode($keyword),'p'=>''),false,false);
		$listpages  = get_cms_page($news_count,C('web_admin_pagenum'),$news_page,$news_url,'条文章');
		$_SESSION['info_reurl'] = $news_url.$news_page;
		//查询数据
		$list = $this->InfoDB->where($where)->order($order)->limit(C('web_admin_pagenum'))->page($news_page)->select();
		if (empty($list)) {
			if($cid || $keyword){
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Info/Show');
				$this->error('没有查询到您所筛选的文章资讯,请重新选择条件!');
			}else{
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Info/Add');
				$this->error('还没有任何资讯,请先添加!');
			}
		}
		foreach($list as $key=>$val){
			$list[$key]['cname']      = get_channel_name($list[$key]['cid']);
			$list[$key]['channelurl'] = U('Admin-Info/Show',array('cid'=>$list[$key]['cid']),false,false);
			$list[$key]['infourl']    = get_read_url('info',$list[$key]['id'],$list[$key]['cid'],$list[$key]['jumpurl']);
			$list[$key]['stararr']    = get_star_arr($list[$key]['stars']);
		}
		$this->assign($listpages);
		$this->assign('order',$order);
		$this->assign('cid',$cid);
		$this->assign('keyword',$keyword);
		$this->assign('list_channel_info',F('_gxcms/channelinfo'));
		$this->assign('list_info',$list);
		$this->display('views/admin/info_show.html');
    }
	// 添加新闻与编辑新闻		
    public function add(){
		$where['id'] = $_GET['id'];
		if ($where['id']) {
			$array = $this->InfoDB->where($where)->find();
			if (C('web_admin_edittime')){
				$array['checktime'] = 'checked';
			}
			$array['tpltitle'] = '编辑';
		}else{
			if($_GET['cid']){
				$array['cid']  = intval($_GET['cid']);
			}else{
		    	$array['cid']  = cookie('info_cid');
			}
			$array['addtime']  = time();
			$array['inputer']  = $_SESSION['user'];
			$array['checktime']= 'checked';
			$array['tpltitle'] = '添加';
		}
		$this->assign($array);
		$this->assign('list_channel_info',F('_gxcms/channelinfo'));
		$this->display('views/admin/info_add.html');
    }
	// 新增新闻保存到数据库
	public function insert(){
		if($this->InfoDB->create()){
			$id = $this->InfoDB->add();
			if( false!== $id){		
			    $this->assign("jumpUrl",C('cms_admin').'?s=Admin/Info/Add');
			}else{
				$this->error('文章添加失败!');
			}
		}else{
		    $this->error($this->InfoDB->getError());
		}
	}
	// 新增新闻保存到数据库-后置操作
	public function _after_insert(){
		cookie('info_cid',intval($_POST["cid"]));
		$this->success('文章添加成功,继续添加新文章！');
	}
	// 更新文章信息
	public function update(){
		if ($this->InfoDB->create()) {
			if (false!==$this->InfoDB->save()) {
			    $this->assign("jumpUrl",$_SESSION['info_reurl']);
			}else{
				$this->error("更新文章信息失败!");
			}
		}else{
			$this->error($this->InfoDB->getError());
		}
	}
	// 编辑文章到数据库-后置操作
	public function _after_update(){
		$id = intval($_POST["id"]);
		if(C('html_cache_on')){
			$id = md5($_POST["id"]).C('html_file_suffix');
			@unlink(HTML_PATH.'Info_detail/'.$id);
		}
		if(C('url_html')){
			$id = $_POST["id"];
			echo'<iframe scrolling="no" src="?s=Admin/Html/Infoid/ids/'.$id.'" frameborder="0" style="display:none"></iframe>';
		}
		$this->success('更新文章信息成功！');	
	}	
	// 隐藏与显示文章
    public function status(){
		$where['id'] = $_GET['id'];
		if($_GET['sid']){
			$this->InfoDB->where($where)->setField('status',1);
		}else{
			$this->InfoDB->where($where)->setField('status',0);
		}
		redirect($_SESSION['info_reurl']);
    }
	// 批量审核与取消审核
    public function statusall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的影片!');
		}	
		$where['id'] = array('in',implode(',',$_POST['ids']));
		if($_GET['sid']){
			$this->InfoDB->where($where)->setField('status',1);
		}else{
			$this->InfoDB->where($where)->setField('status',0);
		}
		redirect($_SESSION['info_reurl']);
    }	
	// 批量转移文章
    public function changecid(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的文章!');
		}	
		$cid = intval($_POST['changecid']);
		if (get_channel_son($cid)) {
			$data['cid'] = $cid;
			$where['id'] = array('in',$_POST['ids']);
			$this->InfoDB->where($where)->save($data);
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Info/Show');
			$this->success('批量转移数据成功！');		    
		}else{
			$this->error('请选择当前大类下面的子分类！');		
		}
    }	
	// 删除文章
    public function del(){
		$this->delfile($_GET['id']);
		redirect($_SESSION['info_reurl']);
    }
	// 删除文章all
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的文章!');
		}	
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->delfile($val);
		}
		redirect($_SESSION['info_reurl']);
    }
	// 删除数据库信息/静态文件/图片
    public function delfile($id){
		$where['id'] = $id;
		//删除静态文件
		$array = $this->InfoDB->field('id,cid,picurl,title')->where($where)->find();
		@unlink('./'.C('upload_path').'/'.$array['picurl']);
		if(C('url_html')){
			//删除内容页
			@unlink(C('webpath').get_read_url_dir('info',$array['id'],$array['cid']).C('html_file_suffix'));
		}			
		//删除专题收录
		$rs = new Model();
	    $rs->execute("update ".C('db_prefix')."special set aids=Replace(Replace(Replace(Replace
			(CONCAT(',,',aids,',,'),',$id,',','),',,,,',''),',,,',''),',,','')");
		//
		$this->InfoDB->where($where)->delete();
    }
	// Ajax推荐星级权重
    public function stars(){
		$where['id']   = $_GET['id'];
		$data['stars'] = intval($_GET['sid']);
		$this->InfoDB->where($where)->save($data);
		exit('ok');
    }
	// Ajax批量下载图片
    public function downimg(){
		$fail    = $_GET['picurl'];
		$downnum = intval(C('upload_http_down'));
		if ($fail) {
			$this->InfoDB->execute('update '.C('db_prefix').'info set picurl=REPLACE(picurl,"fail://", "http://")');
		}
		$count = $this->InfoDB->field('id,picurl')->where('Left(picurl,7)="http://" and status=1')->count('id');		
		$list  = $this->InfoDB->field('id,picurl')->where('Left(picurl,7)="http://" and status=1')->limit($downnum)->order('addtime desc')->select();
		echo('<div style="font-size:12px;padding:5px;padding-left:30px;" id="show">');
		if (is_null($list)) {
			exit('<li>没有检测到远程图片,不需要批量下载!</li>');
		}else{
			echo('<li>共需要下载<font color=red>'.$count.'</font>张远程图片。</li>');
		}
		foreach($list as $key=>$value){
			$down = D('Down');
			$data['picurl'] = $down->down_img($value['picurl']);
			if ($data['picurl'] != $value['picurl']) {
				echo('<li>'.$value['id'].'--'.$value['picurl'].'下载成功!</li>');
			}else{
				echo('<li>'.$value['id'].'--'.$value['picurl'].' <font color=red>保存失败!</font></li>');
				$data['picurl'] = str_replace("http://","fail://",$value['picurl']);
			}
			$this->InfoDB->where('id = '.$value['id'])->save($data);
		}
		echo '<li>请稍等5秒，每页批量下载'.$downnum.'张远程图片,正在准备下一次任务！</li><li style="display:none">';
		redirect(C('cms_admin').'?s=Admin/Info/Downimg',5);
		echo '</li></div>';
    }	
	// 迷你文章列表
    public function showspecial(){
		$specialid = intval($_REQUEST['id']);
		$keyword   = urldecode(trim($_REQUEST['keyword']));
		if ($keyword) {
			$search['title']  = array('like','%'.$keyword.'%');
			$search['remark'] = array('like','%'.$keyword.'%');
			$map              = $search;
			$map['_logic']    = 'or';
			$where['_complex']= $map;
		}		
		$where['status'] = 1;
		$info_count = $this->InfoDB->where($where)->count('id');
		$info_page  = !empty($_GET['p'])?$_GET['p']:1;
		$info_page  = intval($info_page);
		$info_url   = U('Admin-Info/Showspecial',array('id'=>$specialid,'keyword'=>urlencode($keyword),'p'=>''),false,false);
		$listpages  = get_cms_page($info_count,C('web_admin_pagenum'),$info_page,$info_url,'篇文章');
		$list = $this->InfoDB->field('id,cid,title')->where($where)->limit(C('web_admin_pagenum'))->page($info_page)->order('addtime desc')->select();	
		$this->assign($listpages);
		$this->assign('list_info',$list);
		$this->assign('keyword',$keyword);
		$this->display('views/admin/special_info.html');
    }							
}
?>