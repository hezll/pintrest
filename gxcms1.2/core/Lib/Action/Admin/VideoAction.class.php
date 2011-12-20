<?php
/**
 * @name   视频模块
 * @package GXCMS.Administrator
 */
class VideoAction extends AdminAction{
     private $VideoDB;	
     private $UserVDB;
	 private $CommDB;
	 public function _initialize(){
	 	parent::_initialize();
	 	$this->VideoDB =D('Admin.Video');
		$this->UserVDB =D('Admin.Userview');
		$this->CommDB  =D('Admin.Comment');
    }
	// 视频列表		
    public function show(){
    	//get params
		$where  = array();
		$cid    = $_REQUEST['cid'];
		$status = $_REQUEST['status'];
		$serial = $_REQUEST['serial'];
		$picurl = $_REQUEST['picurl'];
		$keyword= urldecode(trim($_REQUEST['keyword']));
		//search condition
		if ($cid) {
			if(get_channel_son($cid)){
				$where['cid']= $cid;
			}else{
				$where['cid']= get_channel_sqlin($cid);
			}
		}
		if ($status || $status==='0') {
			$where['status'] = array('eq',intval($status));
		}
		if ($serial) {
			$where['serial'] = array('neq',0);
		}
		if ($picurl) {
			$where['Left(picurl,7)'] = array('eq','fail://');
		}		
		if ($keyword) {
			$search['title']   = array('like','%'.$keyword.'%');
			$search['intro']   = array('like','%'.$keyword.'%');
			$search['actor']   = array('like','%'.$keyword.'%');
			$search['director']= array('like','%'.$keyword.'%');
			$search['_logic']  = 'or';
			$where['_complex'] = $search;
		}
		//
		$video['type']  = !empty($_GET['type'])?$_GET['type']:C('web_admin_ordertype');
		$video['order'] = !empty($_GET['order'])?$_GET['order']:'desc';
		$order          = $video["type"].' '.$video['order'];
		//
		$video_count = $this->VideoDB->where($where)->count('id');
		$video_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$video_page  = get_cms_page_max($video_count,C('web_admin_pagenum'),$video_page);
		$video_url   = U('Admin-Video/Show',array('cid'=>$cid,'status'=>$status,'serial'=>$serial,'picurl'=>$picurl,'type'=>$video['type'],'order'=>$video['order'],'keyword'=>urlencode($keyword),'p'=>''),false,false);
		$listpages   = get_cms_page($video_count,C('web_admin_pagenum'),$video_page,$video_url,'部影片');
		$_SESSION['video_reurl'] = $video_url.$video_page;
		//
		$list = $this->VideoDB->where($where)->order($order)->limit(C('web_admin_pagenum'))->page($video_page)->select();	
		if (empty($list)) {
			if($status || $serial || $cid || $keyword){
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Video/Show');
				$this->error('没有查询到您所筛选的影片信息,请重新选择条件!');
			}else{
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Video/Add');
				if(empty($picurl))
				$this->error('还没有任何影片,请先添加一部影片!');
			}
		}
		foreach($list as $key=>$val){
			$list[$key]['cname']      = get_channel_name($list[$key]['cid']);
			$list[$key]['channelurl'] = U('Admin-Video/Show',array('cid'=>$list[$key]['cid']),false,false);
			$list[$key]['videourl']   = get_read_url('video',$list[$key]['id'],$list[$key]['cid']);
			$list[$key]['stararr']    = get_star_arr($list[$key]['stars']);
		}
		//dump($this->VideoDB->getLastSql());
		$this->assign($listpages);
		$this->assign('order',$order);
		$this->assign('cid',$cid);
		$this->assign('keyword',$keyword);
		$this->assign('list_channel_video',F('_gxcms/channelvideo'));
		$this->assign('list_video',$list);
		$this->display('views/admin/video_show.html');
    }
	// 添加影片与编辑影片		
    public function add(){
		$where['id'] = $_GET['id'];
		if ($where['id']) {
			$array = $this->VideoDB->where($where)->find();
			if (C('web_admin_edittime')){
				$array['checktime'] = 'checked';
			}
			$array['tpltitle'] = '编辑';
		}else{
			if($_GET['cid']){
				$array['cid'] = intval($_GET['cid']);
			}else{
		    	$array['cid'] = cookie('video_cid');
			}
			$array['addtime']  = time();
			$array['inputer']  = $_SESSION['user'];
			$array['checktime']= 'checked';
			$array['tpltitle'] = '添加';
		}
		$array['list_language']= explode(',',C('web_admin_language'));
		$array['list_area']    = explode(',',C('web_admin_area'));
		$this->assign($array);
		$this->assign('list_channel_video',F('_gxcms/channelvideo'));
		$this->display('views/admin/video_add.html');
    }
	// 前置操作
	public function _before_insert(){
		if (strpos($_POST['picurl'],'://') > 0 && C('upload_http')) {
			$down = D('Down');
			$_POST['picurl']= $down->down_img(trim($_POST['picurl']));
		}
	}	
	// 新增影片保存到数据库
	public function insert(){
		if($this->VideoDB->create()){
			$id = $this->VideoDB->add();
			if( false!== $id){
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Video/Add');
			}else{
				$this->error('影片添加失败!');
			}
		}else{
		    $this->error($this->VideoDB->getError());
		}
	}
	// 新增影片保存到数据库-后置操作
	public function _after_insert(){
		cookie('video_cid',intval($_POST["cid"]));
		$this->success('影片添加成功,继续添加新影片！');
	}
	// 更新影片信息
	public function update(){
		$this->_before_insert();
		if ($this->VideoDB->create()) {
			if (false!==$this->VideoDB->save()) {
			    $this->assign("jumpUrl",$_SESSION['video_reurl']);
			}else{
				$this->error("编辑影片信息失败!");
			}
		}else{
			$this->error($this->VideoDB->getError());
		}
	}
	// 编辑影片保存到数据库-后置操作
	public function _after_update(){
		$id = intval($_POST["id"]);
		if(C('html_cache_on')){
			$id = md5($_POST["id"]).C('html_file_suffix');
			@unlink(HTML_PATH.'Video_detail/'.$id);
			@unlink(HTML_PATH.'Video_play/'.$id);			
		}
		if(C('url_html')){
			$id = $_POST["id"];
			echo'<iframe scrolling="no" src="?s=Admin/Html/Videoid/ids/'.$id.'" frameborder="0" style="display:none"></iframe>';
		}
		$this->success('编辑影片信息成功！');	
	}
	// 删除影片
    public function del(){
		$this->delfile($_GET['id']);
		redirect($_SESSION['video_reurl']);
    }
	// 删除影片all
    public function delall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要删除的影片!');
		}	
		$array = $_POST['ids'];
		foreach($array as $val){
			$this->delfile($val);
		}
		redirect($_SESSION['video_reurl']);
    }
	// 删除静态文件与图片
    public function delfile($id){
		//删除静态文件
		$array = $this->VideoDB->field('id,cid,picurl,title,playurl')->where('id = '.intval($id))->find();
		@unlink('./'.C('upload_path').'/'.$array['picurl']);
		@unlink('./'.C('upload_path').'-s/'.$array['picurl']);
		if(C('url_html')){
			//删除内容页
			@unlink(C('webpath').get_read_url_dir('video',$array['id'],$array['cid']).C('html_file_suffix'));
			//删除播放页
			if(C('url_html_play')){
				$count = 1;
				if(C('url_html_play') == 2){
					$count = $this->playlist($array['playurl'],$array['id'],$array['cid']);
					$count = $count[0]['playcount'];
				}
				for($i=0;$i<$count;$i++){
					$dirurl = get_play_url_dir($array['id'],$array['cid'],$i).C('html_file_suffix');
					@unlink($dirurl);
				}
			}
		}
		//删除专题收录
		$rs = new Model();
	    $rs->execute("update ".C('db_prefix')."special set mids=Replace(Replace(Replace(Replace
			(CONCAT(',,',mids,',,'),',$id,',','),',,,,',''),',,,',''),',,','')");
		//删除影片ID
		$where['id'] = $id;
		$this->VideoDB->where($where)->delete();
		unset($where);
		//删除观看主录
		$where['did'] = $id;
		$this->UserVDB->where($where)->delete();
		unset($where);
		//删除相关评论
		$where['did'] = $id;
		$where['mid'] = 1;
		$this->CommDB->where($where)->delete();
    }
	// 隐藏与显示影片
    public function status(){
		$where['id'] = $_GET['id'];
		if($_GET['sid']){
			$this->VideoDB->where($where)->setField('status',1);
		}else{
			$this->VideoDB->where($where)->setField('status',0);
		}
		redirect($_SESSION['video_reurl']);
    }
	// 批量审核与取消审核
    public function statusall(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的影片!');
		}	
		$where['id'] = array('in',implode(',',$_POST['ids']));
		if($_GET['sid']){
			$this->VideoDB->where($where)->setField('status',1);
		}else{
			$this->VideoDB->where($where)->setField('status',0);
		}
		redirect($_SESSION['video_reurl']);
    }	
	// 批量转移影片
    public function changecid(){
		if(empty($_POST['ids'])){
			$this->error('请选择需要转移的影片!');
		}	
		$cid = intval($_POST['changecid']);
		if (get_channel_son($cid)) {
			$data['cid'] = $cid;
			$where['id'] = array('in',$_POST['ids']);
			$this->VideoDB->where($where)->save($data);
			//$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Video/Show');
			//$this->success('批量转移数据成功！');
			redirect($_SESSION['video_reurl']);
		}else{
			$this->error('请选择当前大类下面的子分类！');		
		}
    }	
	// Ajax设置连载
    public function serial(){
		$where['id']    = $_REQUEST['id'];
		$data['serial'] = trim($_REQUEST['sid']);
		$this->VideoDB->where($where)->save($data);
		exit('ok');
    }	
	// Ajax推荐星级权重
    public function stars(){
		$where['id']   = $_GET['id'];
		$data['stars'] = intval($_GET['sid']);
		$this->VideoDB->where($where)->save($data);
		//$this->ajaxReturn($sid,'ok',1);
		exit('ok');
    }
	// Ajax批量下载图片
    public function downimg(){
		$fail = $_GET['picurl'];
		$downnum = intval(C('upload_http_down'));
		if ($fail) {
			$this->VideoDB->execute('update '.C('db_prefix').'video set picurl = REPLACE(picurl,"fail://", "http://")');
		}
		$count = $this->VideoDB->field('id,picurl')->where('Left(picurl,7)="http://" and status=1')->count('id');
		$list  = $this->VideoDB->field('id,picurl')->where('Left(picurl,7)="http://" and status=1')->limit($downnum)->order('id desc')->select();
		echo('<div style="font-size:12px;padding:5px;" id="show">');
		if (empty($list)) {
			exit('<li>没有检测到远程图片或已经下载完成!</li>');
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
			$this->VideoDB->where('id = '.$value['id'])->save($data);
		}
		echo '<li>请稍等5秒，每页批量下载'.$downnum.'张远程图片,正在准备下一次任务！</li><li style="display:none">';
		redirect(C('cms_admin').'?s=Admin/Video/Downimg',5);
		echo '</li></div>';
    }	
	// 迷你影视列表
    public function showspecial(){
		$specialid = intval($_REQUEST['id']);
		$keyword   = urldecode(trim($_REQUEST['keyword']));
		if ($keyword) {
			$search['title']  = array('like','%'.$keyword.'%');
			$search['intro']  = array('like','%'.$keyword.'%');
			$search['actor']  = array('like','%'.$keyword.'%');
			$map              = $search;
			$map['_logic']    = 'or';
			$where['_complex']= $map;
		}
		$where['status']= 1;
		$video_count = $this->VideoDB->where($where)->count('id');
		$video_page  = !empty($_GET['p'])?intval($_GET['p']):1;
		$video_url   = U('Admin-Video/Showspecial',array('id'=>$specialid,'keyword'=>urlencode($keyword),'p'=>''),false,false);
		$listpages   = get_cms_page($video_count,C('web_admin_pagenum'),$video_page,$video_url,'部影片');
		$list = $this->VideoDB->field('id,cid,title,intro,actor')->where($where)->limit(C('web_admin_pagenum'))->page($video_page)->order('addtime desc')->select();
		$this->assign($listpages);
		$this->assign('list_video',$list);
		$this->assign('keyword',$keyword);
		$this->display('views/admin/special_video.html');
    }					
}
?>