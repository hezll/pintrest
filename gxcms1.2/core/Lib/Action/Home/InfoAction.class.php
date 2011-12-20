<?php
class InfoAction extends HomeAction{
    //文章列表
    public function lists(){
		//获取地址栏参数并读取栏目缓存信息
		$url = get_url_where();
		//获取栏目信息缓存
		if($url['id']){
			$list = list_search(F('_gxcms/channel'),'id='.$url['id']);
		}else{
			$list = F('_gxcms/channel');
		}
		$channel = $list[0];		
		//查询本类及小类共多少条数据
		$where['status'] = array('eq',1);
		if($url['id']){
			if(get_channel_son($url['id'])){
				$where['cid'] = $url['id'];
			}else{
				$where['cid'] = get_channel_sqlin($url['id']);
			}
			$jumpurl['id'] = $url['id'];
		}
		if ($url['order'] != 'addtime') {
			$jumpurl['order'] = $url['order'];
		}
		$rs = M('Info');
		$count = $rs->where($where)->count('id');	
		//组合分页信息(强制为动态模式)
		C('url_html_channel',0);
		$totalpages = ceil($count/$channel['limit']);
		if($url['page'] > $totalpages){ 
			$url['page'] = $totalpages; 
		}				
		$pages = '共'.$count.'篇资讯&nbsp;当前:'.$url['page'].'/'.$totalpages.'页&nbsp;';
		$pages .= get_cms_page_css($url['page'],$totalpages,C('web_home_pagenum'),get_show_url('info',$jumpurl,2),false);
		//整理栏目前台标签数组变量
		$channel['cid']   = $url['id'];
		$channel['order'] = $url['order'];
		$channel['page']  = $url['page'];
		$channel['pages'] = $pages;
		$channel['count'] = $count;
		$channel['webtitle']= $channel['cname'].'-'.C('web_name');
		if($channel['pid']){
			$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <a href="'.$channel['showurl_p'].'">'.$channel['cname_p'].'</a> &gt; <span>'.$channel['cname'].'</span>';
		}else{
			$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <span>'.$channel['cname'].'</span>';	
		}
		//先给bdlist标签传值后再输出模板
		C('bdlist_ids',$where['cid']);
		C('bdlist_page',$url['page']);
		C('bdlist_where',$url['where']);
		if (empty($channel['ctpl'])) {
			$channel['ctpl'] = 'info_list';
		}
		$this->assign($channel);
		$this->display($channel['ctpl']);
    }
    //搜索文章列表
    public function search(){
		//获取地址栏参数并读取栏目缓存信息
		$url = get_url_where();		
		$list = F('_gxcms/channel');$channel = $list[999];
		//搜索条件
		if ($url['wd']) {
			$search['title']  = array('like','%'.$url['wd'].'%');
			$search['remark'] = array('like','%'.$url['wd'].'%');
			$jumpurl['wd']    = urlencode($url['wd']);			
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
		$where['status'] = array('eq',1);						
		$rs = M('Info');
		$count = $rs->where($where)->count('id');
		//组合分页信息(强制为动态模式)
		C('url_html_channel',0);
		$jumpurl['p'] = '';
		$totalpages   = ceil($count/$channel['info']);
		if($url['page'] > $totalpages){ 
			$url['page'] = $totalpages;
		}		
		$pages  = '共'.$count.'篇文章&nbsp;当前:'.$url['page'].'/'.$totalpages.'页&nbsp;';
		$pages .= get_cms_page_css($url['page'],$totalpages,C('web_home_pagenum'),str_replace('/lists','/search',get_show_url('info',$jumpurl,2)),false);
		//前台模板变量赋值
		$channel['cid']     = $url['id'];
		$channel['keyword'] = $url['wd'];
		$channel['order']   = $url['order'];
		$channel['count']   = $count;
		$channel['page']    = $url['page'];
		$channel['pages']   = $pages;
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
		$this->display('info_search');
    }
	//读取资讯内容
    public function detail(){
		$where['id']     = $_GET['id'];
		$where['status'] = array('eq',1);
		$rs = M("Info");
		$array = $rs->where($where)->find();
		if($array){
			$array = $this->tags_info_read($array);//变量赋值
			$this->assign($array['show']);
			$this->assign($array['read']);			
			$this->display('info_detail');
		}else{
			$this->assign("jumpUrl",C('web_path'));
			$this->error('此文章已经删除或未开放,请选择查看其它资讯！');
		}
    }	
	//处理评分
    public function score(){
		$where['id'] = intval($_GET['id']);
		if (!$where['id']) {
			exit('-1');
		}		
		$ajax   = intval($_GET['ajax']);
		$cookie = 'artsc-'.$where['id'];
		if ($_COOKIE[$cookie]) {
			exit('0');//重复
		}		
		$rs = M("Info");
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
		$cookie = 'artud-'.$id;
		if($ajax && isset($_COOKIE[$cookie])){
			exit('0');//重复
		}	
		$rs = M("Info");
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
}
?>