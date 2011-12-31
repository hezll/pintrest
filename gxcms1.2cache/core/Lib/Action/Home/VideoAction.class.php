<?php
class VideoAction extends HomeAction{
    //影视列表
    public function lists(){
		//获取地址栏参数并读取栏目缓存信息
		$url = get_url_where();
		//获取栏目信息缓存
		if($url['id']){
			$list = list_search(F('_gxcms/channel'),'id='.$url['id']);
		}else{
			$list = F('_gxcms/channel');
		}
		$channel  = $list[0];
		//组合查询条件并得到本类及小类条件数据统计
		$where['status'] = array('eq',1);
		if($url['id']){
			if(get_channel_son($url['id'])){
				$where['cid'] = $url['id'];
			}else{
				$where['cid'] = get_channel_sqlin($url['id']);
			}
			$jumpurl['id']    = $url['id'];
		}
		if ($url['year']) {
			$where['year']    = array('eq',$url['year']);
			$jumpurl['year']  = $url['year'];
		}
		if ($url['letter']) {
			$where['letter']  = array('eq',$url['letter']);
			$jumpurl['letter']= $url['letter'];
		}
		if ($url['area']) {
			$where['area']    = array('eq',''.$url["area"].'');
			$jumpurl['area']  = urlencode($url['area']);
		}
		if ($url['order'] != 'addtime') {
			$jumpurl['order'] = $url['order'];
		}		
		$rs = M('Video');
		$count = $rs->where($where)->count('id');
		//组合分页信息(强制为动态模式)
		C('url_html_channel',0);
		$totalpages = ceil($count/$channel['limit']);
		if($url['page'] > $totalpages){
			$url['page'] = $totalpages;
		}				
		$pages  = '共'.$count.'部影片&nbsp;当前:'.$url['page'].'/'.$totalpages.'页&nbsp;';
		$pages .= get_cms_page_css($url['page'],$totalpages,C('web_home_pagenum'),get_show_url('video',$jumpurl,2),false);
		//栏目模板赋值
		$channel['cid']    = $url['id'];
		$channel['order']  = $url['order'];
		$channel['page']   = $url['page'];
		$channel['letter'] = $url['letter'];
		$channel['year']   = $url['year'];
		$channel['area']   = urldecode($url['area']);
		$channel['pages']  = $pages;
		$channel['count']  = $count;
		if ($url['page'] > 1) {
			$channel['webtitle'] = $channel['cname'].'-第'.$url['page'].'页-'.C('web_name');
		}else{	
			$channel['webtitle'] = $channel['cname'].'-'.C('web_name');
		}
		if($channel['pid']){
			$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <a href="'.$channel['showurl_p'].'">'.$channel['cname_p'].'</a> &gt; <span>'.$channel['cname'].'</span>';
		}else{
			$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <span>'.$channel['cname'].'</span>';	
		}
		//先给bdlist标签传值后再输出模板
		//C('bdlist_ids',$where['cid']);
		C('bdlist_page',$url['page']);
		C('bdlist_where',$where);
		if (empty($channel['ctpl'])) {
			$channel['ctpl'] = 'video_list';
		}
		$this->assign($channel);
		$this->display($channel['ctpl']);
    }
    //搜索影视列表
    public function search(){
		//获取地址栏参数并读取缓存信息
		$url = get_url_where();
		$list = F('_gxcms/channel');$channel = $list[999];
		//搜索条件
		$where['status'] = array('eq',1);
		if ($url['wd']) {
			$search['title']   = array('like','%'.$url['wd'].'%');
			//$search['intro'] = array('like','%'.$url['wd'].'%');
			$search['actor']   = array('like','%'.$url['wd'].'%');
			$search['director']= array('like','%'.$url['wd'].'%');
			$jumpurl['wd']     = urlencode($url['wd']);			
		}
		if ($url['year']) {
			$search['year']   = array('eq',$url['year']);
			$jumpurl['year']  = $url['year'];
		}
		if ($url['area']) {
			$search['area']   = array('eq','.$url["area"].');
			$jumpurl['area']  = urlencode($url['area']);
		}
		if ($url['letter']) {
			$search['letter'] = array('eq',$url['letter']);
			$jumpurl['letter']= $url['letter'];
		}		
		if ($url['id']) {
			if(get_channel_son($url['id'])){
				$where['cid'] = $url['id'];
			}else{
				$where['cid'] = get_channel_sqlin($url['id']);
			}
			$jumpurl['id']    = $url['id'];
		}
		if (isset($search)) {
			$search['_logic'] = 'or';
			$where['_complex']= $search;
		}
		if ($url['order'] != 'addtime') {
			$jumpurl['order'] = $url['order'];
		}						
		$rs = M('Video');
		$count = $rs->where($where)->count('id');
		//组合分页信息(强制为动态模式)
		C('url_html_channel',0);
		$jumpurl['p'] = '';
		$totalpages = ceil($count/$channel['video']);
		if($url['page'] > $totalpages){ 
			$url['page'] = $totalpages;
		}		
		$pages = '共'.$count.'部影片&nbsp;当前:'.$url['page'].'/'.$totalpages.'页&nbsp;';
		$pages .= get_cms_page_css($url['page'],$totalpages,C('web_home_pagenum'),str_replace('/lists','/search',get_show_url('video',$jumpurl,2)),false);
		//前台模板变量赋值
		$channel['cid']    = $url['id'];
		$channel['year']   = $url['year'];
		$channel['area']   = $url['area'];
		$channel['keyword']= $url['wd'];
		$channel['order']  = $url['order'];
		$channel['letter'] = $url['letter'];
		$channel['count']  = $count;
		$channel['page']   = $url['page'];
		$channel['pages']  = $pages;
		if ($url['page'] > 1) {
			$channel['webtitle'] = '搜索 '.$url['wd'].' 的结果列表 -第'.$url['page'].'页-'.C('web_name');
		}else{	
			$channel['webtitle'] = '搜索 '.$url['wd'].' 的结果列表 -'.C('web_name');
		}
		$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <span>搜索页</span>';	
		//先给bdsearch标签传值后再输出模板
		C('bdsearch_page',$url['page']);
		C('bdsearch_where',$where);
		$this->assign($channel);
		$this->display('video_search');
    }	
	//影片内容页
    public function detail(){
		$where['id']     = $_GET['id'];
		$where['status'] = array('eq',1);
		$rs = M("Video");
		$array = $rs->where($where)->find();
		if($array){
			$array = $this->tags_video_read($array);//变量赋值
			$this->assign($array['show']);
			$this->assign($array['read']);
			$this->display('video_detail');
		}else{
			$this->assign("jumpUrl",C('web_path'));
			$this->error('此影片已经删除或未开放,请选择观看其它节目！');
		}
    }
	//影片播放页
    public function play(){
		$where['id']     = $_GET['id'];
		$where['status'] = array('eq',1);
		$rs = M("Video");
		$array   = $rs->where($where)->find();
		$playarr = explode('-',$where['id']);//ID与集数分隔
		if($array){
			$array = $this->tags_video_read($array,$playarr);
			$this->assign($array['show']);
			$this->assign($array['read']);
			$this->display('video_play');
		}else{
			$this->assign("jumpUrl",C('web_path'));
			$this->error('此影片已经删除或未开放,请选择观看其它节目！');
		}
    }
	//处理评分
    public function score(){
		$where['id'] = intval($_GET['id']);
		if (!$where['id']) {
			exit('-1');
		}		
		$ajax   = intval($_GET['ajax'])*2;//前台是5分传入
		$cookie = 'movsc-'.$where['id'];
		if ($ajax && $_COOKIE[$cookie]) {
			exit('0');//重复
		}		
		$rs   = M("Video");
		$list = $rs->field('score,scoreer')->where($where)->find();
		if($list){
			if($ajax){
				$array['score']   = number_format(($list['score']*$list['scoreer']+$ajax)/($list['scoreer']+1),1);
				$array['scoreer'] = $list['scoreer']+1;
				$rs->where($where)->save($array);
				setcookie($cookie,'t',time()+intval(C('user_check_time')));
			}else{
				$array = $list;
			}
		}else{
			$array['score']   = 0.0;
			$array['scoreer'] = 0;
		}
		echo($array['score'].':'.$array['scoreer']);
    }
	//处理顶踩
    public function updown(){
		$id = intval($_GET['id']);
		if (!$id) {
			exit('-1');
		}
		$ajax   = trim($_GET['ajax']);
		$cookie = 'movud-'.$id;
		if($ajax && isset($_COOKIE[$cookie])){
			exit('0');//重复
		}
		$rs = M("Video");
		if ('up' == $ajax){
			$rs->setInc('up','id = '.$id);
			setcookie($cookie,'t',time()+intval(C('user_check_time')));
		}elseif( 'down' == $ajax){
			$rs->setInc('down','id = '.$id);
			setcookie($cookie,'t',time()+intval(C('user_check_time')));
		}
		$list = $rs->field('up,down')->find($id);
		if (empty($list)) {
			$list['up']   = 0;
			$list['down'] = 0;
		}
		echo($list['up'].':'.$list['down']);
    }	
	//影片下载
    public function down(){
		$this->display('video_down');
    }					
}
?>