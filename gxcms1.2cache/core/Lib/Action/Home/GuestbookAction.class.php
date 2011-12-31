<?php
class GuestbookAction extends HomeAction{
    public function index(){
		$this->lists();
    }
   //留言列表
    public function lists(){
		$id    = intval($_GET['id']);
		$page  = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$limit = intval(C('user_page_gb'));	
		$order = C('db_prefix').'gbook.addtime desc';
		$join  = C('db_prefix').'user on '.C('db_prefix').'gbook.uid = '.C('db_prefix').'user.id';
		$field = C('db_prefix').'gbook.*,'.C('db_prefix').'user.id as userid,'.C('db_prefix').'user.username,'.C('db_prefix').'user.face';
		if (C('user_check')) {
			$where[C('db_prefix').'gbook.status'] = array('eq',1);
		}		
		$rs = M('gbook');
		$count = $rs->field($field)->where($where)->count('id');
		$page  = get_cms_page_max($count,$limit,$page);
		$list  = $rs->field($field)->where($where)->join($join)->order($order)->limit($limit)->page($page)->select();
		//dump($rs->getLastSql());
		foreach($list as $key=>$val){
			$list[$key]['floor'] = $count-(($page-1) * $limit + $key);
		}
		//强制栏目为动态模式
		C('url_html_channel',0);	
		//分页展示信息
		$totalpages = ceil($count/$limit);
		$pages  = '共'.$count.'篇留言&nbsp;当前:'.$page.'/'.$totalpages.'页&nbsp;';
		$pages .= get_cms_page_css($page,$totalpages,C('web_home_pagenum'),get_show_url('guestbook','',2),false);		
		if($id){
			$rs = M("Video");
			$arr=$rs->field('id,title')->where('status=1 and id='.$id)->find();
			if($arr){
				$this->assign('content','影片<'.$arr['title'].'>不能观看，请修复！');
			}
		}		
		$this->assign('webtitle','留言本'.'-'.C('web_name'));
		$this->assign('navtitle','<a href="'.C('web_path').'">首页</a> &gt; <span>留言本</span>');
		$this->assign('list_guestbook',$list);
		$this->assign('pages',$pages);
		$this->assign('count',$count);
		$this->assign('id',$id);
		$this->display('guestbook');
    }	
	// 添加留言
    public function insert(){
		$rs = D("Home.Gbook");
		C('TOKEN_ON',false);//关闭令牌验证
		if($rs->create()){
			if (false !== $rs->add()) {
				$cookie = 'gbook-'.intval($_POST['errid']);
				setcookie($cookie, 'true', time()+intval(C('user_check_time')));
				if($_POST['errid']>0) {
				  $showname='报错';	
				}else{
				  $showname='留言';	
				}
				if (C('user_check')) {
					$this->success($showname.'成功，我们会尽快审核！');
				}else{
					$this->success('恭喜您,'.$showname.'成功！');
				}
			}else{
				$this->error($showname.'失败，请重试！');
			}
		}else{
		    $this->error($rs->getError());
		}
    }
}
?>