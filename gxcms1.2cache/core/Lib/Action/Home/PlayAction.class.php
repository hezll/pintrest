<?php
class PlayAction extends HomeAction{
	//写入记录与控制观看收费
    public function show(){
		$id = intval($_GET['id']);
		if ($id) {
			$this->checkuser($id);
			$rs = M("Video");
			$playurl = $rs->where('id='.$id)->getField('playurl');
			$player  ='<script language="javascript" type="text/javascript">'."\n";
			$player .='var $playlist="'.str_replace(array("\r\n", "\n", "\r"),'+++',$playurl).'"'."\n";
			$player .='</script>'."\n";
			$player .='<iframe src="./views/player/index.html" width="'.C('player_width').'" height="'.C('player_height').'" marginWidth="0" frameSpacing="0" marginHeight="0" frameBorder="0" border="0" scrolling="no" vspale="0" style="z-index:1;" noResize></iframe>'."\n";
			$player  ='<script language="javascript" type="text/javascript">'."\n";
			$player .='var $playlist="'.str_replace(array("\r\n", "\n", "\r"),'+++',$playurl).'"'."\n";
			$player .='</script>'."\n";
			$player .='<script language="javascript" src="'.C('web_path').'views/js/player.js" charset="utf-8"></script>';							
			echo($player);
		}else{
			$this->assign('title','对不起，无此影片!');
			$this->display('./views/tips/pay.html');
		}
	}
	public function checkuser($id){
		$userid   = intval($_COOKIE['gx_userid']);
		$username = $_COOKIE['gx_username'];
		$userpwd  = $_COOKIE['gx_userpwd'];
		if(!$userid){
			$this->assign('title','对不起，您还没有<a href="'.C('web_path').'index.php?s=User/Login">登录</a>！ <a href="'.C('web_path').'index.php?s=User/Reg">新用户注册</a>');
			$this->display('./views/tips/pay.html');
			exit();
		}
		$rs = M("user");
		$where['id'] = array('eq',$userid);
		$list = $rs->field('id,username,userpwd,money,pay,duetime')->where($where)->find();
		if (empty($list)) {
			$this->assign('title','对不起，未找到匹配用户!');
			$this->display('./views/tips/pay.html');
			exit();			
		}
		if ($username !== $list['username'] || $userpwd !== $list['userpwd']) {
			$this->assign('title','对不起，Cookie验证失败!');
			$this->display('./views/tips/pay.html');
			exit();
		}
		//检查金币
		if($list['pay']){
			//包月用户检查时间
			if ($list['duetime'] < time()){
				$data['id']  = $list['id'];
				$data['pay'] = 0;
				$rs->save($data);
				$this->assign('title','对不起，包月已过期，请<a href="'.C('web_path').'index.php?s=User/Shop"">充值</a>！');
				$this->display('./views/tips/pay.html');
				exit();				
			}
		}else{
			//扣点用户权限
			if ($list['money'] < intval(C('user_money_play'))){
				$this->assign('title','对不起，积分不够，请<a href="'.C('web_path').'index.php?s=User/Shop"">充值</a>！');
				$this->display('./views/tips/pay.html');
				exit();			
			}
			//扣积分
			$rs->setDec('money','id='.$list['id'],intval(C('user_money_play')));
		}
		//写入观看记录
		$this->insertview($id);
	}
	//保存观看记录
	public function insertview($did){
	    $userid = intval($_COOKIE['gx_userid']);
		if ($userid) {
			$rs = M('Userview');
			$data['did'] = $did;
			$data['uid'] = $userid;
			$list = $rs->field('id,did,uid')->where($data)->find();
			if($list){
				$up['id']       = $list['id'];
				$up['viewtime'] = time();
				$rs->save($up);
			}else{
				$data['viewtime']= time();
				$rs->add($data);
			}
		}
	}
	//收费提示
	public function login(){
	    $this->assign("jumpUrl",C('web_path').'index.php?s=user/login');
		$this->error('观看该影片需要支付'.C('user_money_play').'个影币,请先从用户中心登录！');
	}
	//包月过期提示
	public function pass(){
	    $this->assign("jumpUrl",C('web_path').'index.php?s=user/index');
		$this->error('对不起,包月帐户已经到期,请联系管理员充值！');
	}
	//积分不够 
	public function money(){
	    $this->assign("jumpUrl",C('web_path').'index.php?s=user/index');
		$this->error('对不起,你的影币不够,请联系管理员充值！');
	}		
}
?>