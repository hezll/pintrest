<?php
require_once(dirname(__FILE__).'/xml.class.php');
require_once(dirname(__FILE__).'/collect.class.php');
//生成热门关键词JS
function get_admin_hotkey($string){
	$array = explode('|',trim($string));
	$hotkey = '';
	foreach($array as $key=>$value){
		$hotkey .= 'document.write(\'<a href="'.C('web_path').'index.php?s=video/search/wd/'.urlencode($value).'">'.$value.'</a>\');'."\n";
	}
	write_file('./temp/Js/hot.js',$hotkey);
}
// 获取数据库表名描述
function get_table_name($tablename){
	if (strpos($tablename,'adsense')>0){
		return '广告';
	}
	if (strpos($tablename,'info')>0){
		return '文章';
	}
	if (strpos($tablename,'video')>0){
		return '影视';
	}
    if (strpos($tablename,'co_channel')>0){
		return '自定义栏目转换';
	}
    if (strpos($tablename,'co_content')>0){
		return '采集内容';
	}
    if (strpos($tablename,'co_node')>0){
		return '采集项目';
	}
    if (strpos($tablename,'co_urls')>0){
		return '采集影片地址';
	}
	if (strpos($tablename,'channel')>0){
		return '栏目';
	}
	if (strpos($tablename,'comment')>0){
		return '评论信息';
	}
	if (strpos($tablename,'gbook')>0){
		return '留言本';
	}
	if (strpos($tablename,'master')>0){
		return '管理用户';
	}
	if (strpos($tablename,'special')>0){
		return '专题';
	}
	if (strpos($tablename,'userview')>0){
		return '消费记录';
	}
	if (strpos($tablename,'slide')>0){
		return '专题';
	}	
	if (strpos($tablename,'link')>0){
		return '友情链接';
	}
	if (strpos($tablename,'user')>0){
		return '用户中心';
	}											
}
// 获取模板分页数量
function get_tpl_limit($rule,$tplname){
	$content = read_file(TMPL_PATH.C('default_theme').'/Home/'.trim($tplname).'.html');
	preg_match('/'.$rule.'/', $content, $data);
	return $data[2];
}
// 获取模板名称
function get_tpl_name($filename){
	switch ($filename) { 
		case 'footer.html':
		   return '模板尾文件';
		case 'header.html': 
		   return '模板头文件';
		case 'video_channel.html': 
		   return '影视频道页模板';
		case 'video_list.html': 
		   return '影视分类页模板';
		case 'video_search.html': 
		   return '影视搜索页模板';		   
		case 'video_detail.html': 
		   return '影视内容页模板';
		case 'video_play.html': 
		   return '影视播放页模板';		   
		case 'info_channel.html': 
		   return '文章频道页模板';
		case 'info_list.html': 
		   return '文章分类页模板';
		case 'info_search.html': 
		   return '文章搜索页模板';		   
		case 'info_detail.html': 
		   return '文章内容页模板';
		case 'special_list.html': 
		   return '专题分类页模板';
		case 'special_detail.html':
		   return '专题内容页模板';
		case 'comment.html':
		   return '评论模板';
		case 'guestbook.html':
		   return '留言本模板';	
		case 'index.html':
		   return '首页模板';
		case 'system.html':
		   return '系统AJAX公用模块';
		case 'system_updown.html':
		   return '默认顶踩标签模块'; 
		case 'system_score.html':
		   return '默认评分标签模块';
		case 'top10.html':
		   return '排行榜模板';		   		   		   	   	   		   		   		   
		default:
			if (ereg('\.gif|\.jpg|\.png|\.bmp',$filename)) {
				return '图片文件';
			}
			if (ereg('\.css',$filename)) {
				return '样式表文件';
			}
			if (ereg('\.js',$filename)) {
				return '脚本文件';
			}
			if (ereg('\.txt',$filename)) {
				return '文本文件';
			}
			if (ereg('my_',$filename)) {
				return '自定义模板文件';
			}					
			if (ereg('\.html|\.htm',$filename)) {
				return '静态网页文件';
			}											
		    return '自定义文件'; 
	}
}
//星级转化数组
function get_star_arr($stars){
	for ($i=1; $i<=5; $i++) {
		if ($i <= $stars){
			$ss[$i]=1;
		}else{
			$ss[$i]=0;
		}
	}
	return $ss;
}
// 测试写入文件
function testwrite($d){
	$tfile = 'gxcms.txt';
	$d = ereg_replace('/$','',$d);
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp){
		return false;
	}else{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs){
			return true;
		}else{
			return false;
		}
	}
}
// 获取文件夹大小
function getdirsize($dir){ 
	$dirlist = opendir($dir);
	while (false !== ($folderorfile = readdir($dirlist))){ 
		if($folderorfile != "." && $folderorfile != "..") { 
			if (is_dir("$dir/$folderorfile")) { 
				$dirsize += getdirsize("$dir/$folderorfile"); 
			}else{ 
				$dirsize += filesize("$dir/$folderorfile"); 
			}
		}    
	}
	closedir($dirlist);
	return $dirsize;
}
function getrexie($str){
	return str_replace('@@@','',str_replace('/@@@','',$str.'@@@'));
}
function getaddxie($str){
	return str_replace('@@@','',str_replace('//@@@','/',$str.'/@@@'));
}



function GetReq($requests, $input) {
		if (!is_array($input) || !is_array($requests)) {
			return array();
		}
		$data = array();
		foreach ($input as $key => $type) {
			$item = $requests[$key];
			if (strtolower($type) == 'trim') {
				$item = trim($item);
			} elseif(@!settype($item, $type)) {
				die('Check the type of the item "' . $key . '" in input array');
			}
			$data[$key] = $item;
		}
		return $data;
	}
?>