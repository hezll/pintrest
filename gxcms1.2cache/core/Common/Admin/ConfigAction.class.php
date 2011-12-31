<?php
class ConfigAction extends AdminAction{
	// 基本信息设置
    public function conf(){
		$id = trim($_GET['id']);
		$config = require './config.php';
		$tpl = './template/*';
		$list = glob($tpl);
		foreach($list as $i => $file){
			$temp[$i]['filename']=basename($file);
		}
		if ($id == 'user'){
			$rsc = D("Admin.Channel");
			$channel_list = $rsc->field('id,pid,mid,cname')->where('mid = 1')->order('id asc')->select();
			$channel_tree = list_to_tree($channel_list,'id','pid','son',0);
			$this->assign('channel_tree',$channel_tree);
		}
		$this->create_channel();
		$this->assign('temp',$temp);
		$this->assign($config);
	    $this->display('./views/admin/con_'.$id.'.html');
    }
	// 配置信息保存
    public function updateconfig($config){
		$config_old = require './config.php';
		$config_new = array_merge($config_old,$config);
		arr2file('./config.php',$config_new);
		@unlink('./temp/~app.php');
		//动态模式则删除首页静态文件
		if(ACTION_NAME=='Updateurl'){
			if (!$config_new['url_html']) {
				@unlink('./index'.C('html_file_suffix'));
			}
		}
		$this->success('恭喜您，配置信息更新成功！');
		
	}
    public function updateweb(){
		$config = $_POST["con"];
		$config['web_admin_pagenum'] = abs(intval($config['web_admin_pagenum']));
		$config['web_admin_hits'] = abs(intval($config['web_admin_hits']));
		$config['web_admin_score'] = abs(intval($config['web_admin_score']));
		$config['web_admin_updown'] = abs(intval($config['web_admin_updown']));
		$config['web_url'] = getaddxie($config['web_url']);
		$config['web_path'] = getaddxie($config['web_path']);
		$config['web_adsensepath'] = getrexie($config['web_adsensepath']);
		$config['web_tongji'] = stripslashes($config['web_tongji']);
		$config['web_admin_edittime'] = (bool) $config['web_admin_edittime'];
		$config['web_collect_num'] = abs(intval($config['web_collect_num']));
		mkdirss('./'.$config['web_adsensepath']);
		get_admin_hotkey($config['web_hotkey']);
		$this->updateconfig($config);
	}
    public function updateup(){
		$config = $_POST["con"];
		$config['upload_thumb_w'] = abs(intval($config['upload_thumb_w']));
		$config['upload_thumb_h'] = abs(intval($config['upload_thumb_h']));
		$config['upload_water_pct'] = abs(intval($config['upload_water_pct']));
		$config['upload_water_pos'] = abs(intval($config['upload_water_pos']));
		$config['upload_http_down'] = abs(intval($config['upload_http_down']));
		$config['upload_ftp_port'] = abs(intval($config['upload_ftp_port']));
		mkdirss('./'.C('upload_path'));
		$this->updateconfig($config);
	}
    public function updatecache(){
		$config = $_POST["con"];
		$config['html_cache_index'] = abs(intval($config['html_cache_index']));
		$config['html_cache_list'] = abs(intval($config['html_cache_list']));
		$config['html_cache_content'] = abs(intval($config['html_cache_content']));
		$config['html_cache_play'] = abs(intval($config['html_cache_play']));		
		$config['tmpl_cache_on'] = (bool) $config['tmpl_cache_on'];
		$config['html_cache_on' ]= (bool) $config['html_cache_on'];
		$config['html_cache_time'] = $config['html_cache_time']*3600;
		if ($config['html_cache_index'] > 0) {
			$config['_htmls_']['home:index:index'] = array('{:action}',$config['html_cache_index']*3600);
		}else{
			$config['_htmls_']['home:index:index'] = NULL;
		}
		if ($config['html_cache_list'] > 0) {
		    $config['_htmls_']['home:video:lists'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_list']*3600);
			$config['_htmls_']['home:info:lists'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_list']*3600);
		}else{
		    $config['_htmls_']['home:video:lists'] = NULL;
			$config['_htmls_']['home:info:lists'] = NULL;
		}
		if ($config['html_cache_content'] > 0) {
		    $config['_htmls_']['home:video:detail'] = array('{:module}_{:action}/{id|md5}',$config['html_cache_content']*3600);
			$config['_htmls_']['home:info:detail'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_content']*3600);
		}else{
		    $config['_htmls_']['home:video:detail'] = NULL;
			$config['_htmls_']['home:info:detail'] = NULL;
		}
		if ($config['html_cache_play'] > 0) {
		    $config['_htmls_']['home:video:play'] = array('{:module}_{:action}/{id|md5}',$config['html_cache_play']*3600);
		}else{
		    $config['_htmls_']['home:video:play'] = NULL;
		}						
		if ($config['html_cache_mytpl'] > 0) {
		    $config['_htmls_']['home:my:show'] = array('{:module}_{:action}/{$_SERVER.REQUEST_URI|md5}',$config['html_cache_mytpl']*3600);
		}else{
		    $config['_htmls_']['home:my:show'] = NULL;
		}
		$this->updateconfig($config);
	}
    public function updateurl(){
		$config = $_POST["con"];
		$config['url_create_num'] = abs(intval($config['url_create_num']));
		$config['url_create_time'] = abs(intval($config['url_create_time']));		
		$this->updateconfig($config);
	}
    public function updateuser(){
		$config = $_POST["con"];
		$config['user_money_play'] = abs(intval($config['user_money_play']));
		$config['user_money_change'] = abs(intval($config['user_money_change']));
		$config['user_money_add'] = abs(intval($config['user_money_add']));
		$config['user_check_time'] = abs(intval($config['user_check_time']));	
		$config['user_page_cm'] = abs(intval($config['user_page_cm']));
		$config['user_page_gb'] = abs(intval($config['user_page_gb']));					
		if (empty($config['user_paycid'])){
			$config['user_paycid'] = '';
		}
		$this->updateconfig($config);
	}
    public function updatedb(){
		$config = $_POST["con"];
		$config['db_port'] = abs(intval($config['db_port']));
		$this->updateconfig($config);
	}
    public function updatenav(){
		$config = $_POST["con"];
		if(empty($config["web_admin_nav"])){
			$this->error('自定义菜单不能为空！');
		}
		foreach(explode(chr(13),trim($config["web_admin_nav"])) as $value){
			list($key,$val) = explode('|',trim($value));
			if(!empty($val)){
				$arrlist[trim($key)] = trim($val);
			}
		}
		$config['web_admin_nav'] = $arrlist;
		$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Config/Conf/id/nav/reload/1');
		$this->updateconfig($config);
	}
    public function updateplayer(){
		$config = $_POST["con"];
		$config['player_width'] = abs(intval($config['player_width']));
		$config['player_height'] = abs(intval($config['player_height']));
		$player ='var player_width='.$config['player_width'].';';
		$player .='var player_height='.$config['player_height'].';';
		$player .='var player_down="'.$config['player_down'].'";';
		$player .='var player_buffer="'.$config['player_buffer'].'";';
		$player .='var player_pause="'.$config['player_pause'].'";'."\n";
		$player .='if(!window.ActiveXObject){alert(\'请使用IE内核浏览器观看本站影片!\');}'."\n";
		write_file('./temp/Js/player.js',$player);	
		$this->updateconfig($config);
	}
}
?>