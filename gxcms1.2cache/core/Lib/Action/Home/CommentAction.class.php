<?php
class CommentAction extends HomeAction{
    public function index(){
		$this->show();
    }
    //评论列表
    public function lists(){
		$mid   = intval($_GET['mid']);
		$did   = intval($_GET['did']);
		$page  = !empty($_GET['p']) ? intval($_GET['p']) : 1;
		$limit = intval(C('user_page_cm'));	
		$order = C('db_prefix').'comment.addtime desc';
		$join  = C('db_prefix').'user on '.C('db_prefix').'comment.uid = '.C('db_prefix').'user.id';
		$field = C('db_prefix').'comment.*,'.C('db_prefix').'user.id as userid,'.C('db_prefix').'user.username,'.C('db_prefix').'user.face';
		$where[C('db_prefix').'comment.did'] = array('eq',$did );
		$where[C('db_prefix').'comment.mid'] = array('eq',$mid );
		if (C('user_check')) {
			$where[C('db_prefix').'comment.status'] = array('eq',1);
		}		
		$rs = M('comment');
		$count = $rs->field($field)->where($where)->count('id');
		$totalpages = ceil($count/$limit);
		//
		$list = $rs->field($field)->where($where)->join($join)->order($order)->limit($limit)->page($page)->select();
		foreach($list as $key=>$val){
			$list[$key]['floor'] = $count-(($page-1) * $limit + $key);
		}
		//分页链接参数
		$pageurl='javascript:void(0)" onclick="CommentShow(\''.U('comment/lists',array('mid'=>$mid,'did'=>$did,'p'=>'{!page!}'),false,false).'\')';
		$pages  = '共'.$count.'条评论&nbsp;当前:'.$page.'/'.$totalpages.'页&nbsp;';
		$pages .= get_cms_page_css($page,$totalpages,C('user_page_cm'),$pageurl,false);
		$this->assign('mid',$mid);
		$this->assign('did',$did);
		$this->assign('pages',$pages);
		$this->assign('count',$count);
		$this->assign('list_comment',$list);
		$this->display('comment');
    }
	// 添加评论
    public function insert(){
		$rs = D("Home.Comment");
		C('TOKEN_ON',false);//关闭令牌验证
		if($rs->create()){
			if (false !== $rs->add()) {
				$cookie = 'comment-'.intval($_POST['mid']).'-'.intval($_POST['did']);
				setcookie($cookie, 'true', time()+intval(C('user_check_time')));
				if (C('user_check')) {
					$this->ajaxReturn($result,"评论成功，我们会尽快审核你的评论！",1);
				}else{
					$this->ajaxReturn($result,"评论成功，感谢你的参与！",1);
				}
			}else{
				$this->ajaxReturn(0,'评论失败，请重试！',1);
			}
		}else{
		    $this->error($rs->getError());
		}
    }
	// Ajax顶踩
    public function updown(){
		$id = intval($_GET['id']);
		if ($id < 1) {
			exit('-1');
		}
		$ajax   = trim($_GET['ajax']);
		$cookie = 'cmud-'.$id;
		if(isset($_COOKIE[$cookie])){
			exit('0');
		}
		$rs = M("Comment");		
		if ('up' == $ajax){
			$rs->setInc('up','id = '.$id);
			setcookie($cookie, 'true', time()+intval(C('user_check_time')));
		}elseif( 'down' == $ajax){		
			$rs->setInc('down','id = '.$id);
			setcookie($cookie, 'true', time()+intval(C('user_check_time')));
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