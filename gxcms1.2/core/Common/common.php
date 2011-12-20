<?php
// 数组保存到文件
function arr2file($filename, $arr=''){
	if(is_array($arr)){
		$con = var_export($arr,true);
	} else{
		$con = $arr;
	}
	$con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
	write_file($filename, $con);
}
function mkdirss($dirs,$mode=0777) {
	if(!is_dir($dirs)){
		mkdirss(dirname($dirs), $mode);
		return @mkdir($dirs, $mode);
	}
	return true;
}
function write_file($l1, $l2=''){
	$dir = dirname($l1);
	if(!is_dir($dir)){
		mkdirss($dir);
	}
	return @file_put_contents($l1, $l2);
}
function read_file($l1){
	return @file_get_contents($l1);
}
// 转换成JS
function t2js($l1, $l2=1){
    $I1 = str_replace(array("\r", "\n"), array('', '\n'), addslashes($l1));
    return $l2 ? "document.write(\"$I1\");" : $I1;
}
//utf8转gbk
function u2g($str){
	return iconv("UTF-8","GBK",$str);
}
//gbk转utf8
function g2u($str){
	return iconv("GBK","UTF-8//ignore",$str);
}
//获取当前地址栏URL
function http_url(){
	return htmlspecialchars("http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);
}
//获得某天前的最后一秒时间戳
function xtime($day){
	$day = intval($day);
	return mktime(23,59,59,date("m"),date("d")-$day,date("y"));
}
// 获取相对目录
function get_base_path($filename){
    $base_path = $_SERVER['PHP_SELF'];
    $base_path = substr($base_path,0,strpos($base_path,$filename));
	return $base_path;
}
// 获取相对路径
function get_base_url($baseurl,$url){
	if("#" == $url){
		return "";
	}elseif(FALSE !== stristr($url,"http://")){
		return $url;
	}elseif( "/" == substr($url,0,1) ){
		$tmp = parse_url($baseurl);
		return $tmp["scheme"]."://".$tmp["host"].$url;
	}else{
		$tmp = pathinfo($baseurl);
		return $tmp["dirname"]."/".$url;
	}
}
//输入过滤 同时去除连续空白字符可参考扩展库的remove_xss
function get_replace_input($str,$rptype=0){
	$str = stripslashes($str);
	$str = htmlspecialchars($str);
	$str = get_replace_nb($str);
	return addslashes($str);
}
//去除换行
function get_replace_nr($str){
	$str = str_replace(array("<nr/>","<rr/>"),array("\n","\r"),$str);
	return trim($str);
}
//去除连续空格
function get_replace_nb($str){
	$str = str_replace("&nbsp;",' ',$str);
	$str = str_replace("　",' ',$str);
	$str = ereg_replace("[\r\n\t ]{1,}",' ',$str);
	return trim($str);
}
//去除所有标准的HTML代码
function get_replace_html($str, $start=0, $length, $charset="utf-8", $suffix=false){
	return msubstr(eregi_replace('<[^>]+>','',ereg_replace("[\r\n\t ]{1,}",' ',get_replace_nb($str))),$start,$length,$charset,$suffix);
}
//返回安全的order
function get_replace_order($order){
	$arrorder['addtime'] = 'addtime';
	$arrorder['id'] = 'id';
	$arrorder['hits'] = 'hits';
	$arrorder['monthhits'] = 'monthhits';
	$arrorder['weekhits'] = 'weekhits';
	$arrorder['dayhits'] = 'dayhits';
	$arrorder['stars'] = 'stars';
	$arrorder['up'] = 'up';
	$arrorder['down'] = 'down';
	$arrorder['score'] = 'score';
	$arrorder['scoreer'] = 'scoreer';
	return $arrorder[trim($order)];
}
//判断是否属于当前模块
function check_model($modelname){
	if(strtolower(MODULE_NAME) == $modelname){
		return 1;
	}
	return 0;
}
//获取模型名称
function get_model_name($mid){
	if ($mid==1){
		return 'video';
	}elseif ($mid==2){
		return 'info';
	}elseif ($mid==3){
		return 'special';
	}elseif ($mid==4){
		return 'user';
	}
}
//获取模型ID
function get_model_id($str){
	if ($mid=='video'){
		return 1;
	}elseif ($mid=='info'){
		return 2;
	}elseif ($mid=='special'){
		return 3;
	}elseif ($mid=='user'){
		return 4;
	}
}
//检查是否没有小类
function get_channel_son($pid){
	$tree = list_search(F('_gxcms/channeltree'),'id='.$pid);
	if(!empty($tree[0]['son'])){
		return false;
	}else{
	    return true;
	}
}
//通过栏目ID获取对应的栏目名称/别名等
function get_channel_name($cid,$type='cname'){
    $arr = list_search(F('_gxcms/channel'),'id='.$cid);
	if (is_array($arr)) {
		return $arr[0][$type];
	}else{
	    return $cid;
	}
}
//通过栏目ID返回其它值数组方式
function get_channel_array($cid,$type='id'){
    $tree = list_search(F('_gxcms/channeltree'),'id='.$cid);
	if(!empty($tree[0]['son'])){
		foreach($tree[0]['son'] as $val){
			$param[] = $val[$type];
		}
		return $param;
	}else{
		return false;
	}
}
//获取栏目数据统计
function get_channel_count($cid=0){
	$where['status'] = 1;
	if ($cid > 0) {
		$where['cid'] = get_channel_sqlin($cid);
	}elseif ($cid == 0) {
		$where['addtime'] = array('gt',xtime(1));
	}
	$mid = get_channel_name($cid,'mid');
	if ($mid == 2){
		$rs = M("Info");
	}else{
		$rs = M("Video");
	}
	$count = $rs->where($where)->count('id');
	return $count+0;
}
//生成栏目sql查询语句范围
function get_channel_sqlin($cid){
	$tree = list_search(F('_gxcms/channeltree'),'id='.$cid);
	if (!empty($tree[0]['son'])) {
		foreach($tree[0]['son'] as $val){
			$arr['cid'][] = $val['id'];
		}
		$channel = implode(',', $arr['cid']);
		return array('IN',''.$channel.'');	
	}
	return $cid;
}
//去重后的listid_array $cids = array(1,2,3,...)
function get_channel_remove($cids){
	foreach($cids as $key=>$value){
		if(get_channel_son($value)){
			$cid .= ','.$value;
		}else{
			$cidin = get_channel_sqlin($value);
			$cid .= ','.$cidin[1];
		}
	}
	$cidarr = explode(',',$cid);
	unset($cidarr[0]);
	$cidarr = array_unique($cidarr);
	return $cidarr;
}
//处理最大分页参数
function get_cms_page_max($count,$limit,$page){
	$totalPages = ceil($count/$limit); 
	if ($page > $totalPages){
		$page = $totalPages;
	}
	return $page;
}
//分页函数
function get_cms_page($totalrecords,$pagesize,$currentpage,$params,$filename='条数据',$pagego=true,$halfPer=5){
	$page['totalrecords'] = $totalrecords;  //总记录数
	$page['totalpages'] = ceil($page['totalrecords']/$pagesize); //总页数
	$page['currentpage'] = $currentpage; //当前页数
	$page['urlpage'] = $params.'{!page!}';
	$page['listpages'] = '共'.$page['totalrecords'].$filename.'&nbsp;当前：'.$page['currentpage'].'/'.$page['totalpages'].'页&nbsp;';
	if ($pagego){ $pagego = 'jumpurl(\''.$page['urlpage'].'\','.$page['totalpages'].')'; }
	$page['listpages'] .= get_cms_page_css($page['currentpage'],$page['totalpages'],$halfPer,$page['urlpage'],$pagego);
	return $page;
}
//分页样式
function get_cms_page_css($currentPage,$totalPages,$halfPer=5,$url,$pagego){
    $linkPage .= ( $currentPage > 1 )
        ? '<a href="'.str_replace('{!page!}',1,$url).'" class="pagegbk">首页</a>&nbsp;<a href="'.str_replace('{!page!}',($currentPage-1),$url).'" class="pagegbk">上一页</a>&nbsp;' 
        : '<em>首页</em>&nbsp;<em>上一页</em>&nbsp;';
    for($i=$currentPage-$halfPer,$i>1||$i=1,$j=$currentPage+$halfPer,$j<$totalPages||$j=$totalPages;$i<$j+1;$i++){
        $linkPage .= ($i==$currentPage)?'<span>'.$i.'</span>&nbsp;':'<a href="'.str_replace('{!page!}',$i,$url).'">'.$i.'</a>&nbsp;'; 
    }
    $linkPage .= ( $currentPage < $totalPages )
        ? '<a href="'.str_replace('{!page!}',($currentPage+1),$url).'" class="pagegbk">下一页</a>&nbsp;<a href="'.str_replace('{!page!}',$totalPages,$url).'" class="pagegbk">尾页</a>'
        : '<em>下一页</em>&nbsp;<em>尾页</em>';
	if(!empty($pagego)){
		$linkPage .='&nbsp;<input type="input" name="page" id="page" class="pageinput"/><input type="button" value="跳 转" onclick="'.$pagego.'" class="pagebg"/>';
	}
    return str_replace('_1'.C('html_file_suffix'),C('html_file_suffix'),str_replace('index1'.C('html_file_suffix'),'',$linkPage));
}
// 获取广告调用地址
function get_cms_ads($str,$charset="utf-8"){
	return '<script type="text/javascript" src="'.C('web_path').C('web_adsensepath').'/'.$str.'.js" charset="utf-8"></script>';
}
// 获取标题颜色
function get_color_title($str,$color){
	if (empty($color)) {
	    return $str;
	}else{
	    return '<font color="'.$color.'">'.$str.'</font>';
	}
}
// 获取时间颜色
function get_color_date($type='Y-m-d H:i:s',$time,$color='red'){
	if($time > xtime(1)){
		return '<font color="'.$color.'">'.date($type,$time).'</font>';
	}else{
	    return date($type,$time);
	}
}
// 获取热门关键词
function get_hot_key($string){
	$array = explode('|',$string);
	if(C('url_html')){
		return '<script type="text/javascript" src="'.C('web_path').'temp/Js/hot.js" charset="utf-8"></script>';
	}
	$hotkey = '';
	foreach($array as $key=>$value){
		$hotkey .= '<a href="'.C('web_path').'index.php?s=video/search/wd/'.urlencode($value).'">'.$value.'</a>';
	}
	return $hotkey;
}
//积分效果
function get_jifen($fen){
	$array = explode('.',$fen);
	return '<strong>'.$array[0].'</strong>.'.$array[1];
}
//关键字高亮
function get_hilight($string,$keyword,$classname='kw-hilight'){
	return str_replace($keyword,'<span class="kw-hilight">'.$keyword.'</span>',$string);
}
// 主演带链接
function get_actor_url($str,$num,$keyword,$classname){
    $str = str_replace(' ','/',str_replace(',','/',$str));
	$arr = explode('/',$str);
	foreach($arr as $key=>$val){
		$value = $val;
		if($keyword){
			$value = get_hilight($value,$keyword,'a');
		}
		$restr .= '<a href="'.C('web_path').'index.php?s=video/search/wd/'.urlencode($val).'" target="_blank">'.$value.'</a> ';
		if(($key+1) == $num){
			break;
		}
	}
	return $restr;	
}
// 字母分类筛选链接
function get_letter_url($cid,$letter='',$mid='video'){
	if($cid){
		$arrurl['id'] = $cid;
	}
    for($i=1;$i<=26;$i++){
	   $arrurl['letter'] = chr($i+64);
	   $url = str_replace('index.php?s=/Home/','index.php?s=',U('Home-'.$mid.'/lists',$arrurl,false,true));
	   if($letter == $arrurl['letter']){
	   		$str .='<a href="'.$url.'" class="Letter">'.$arrurl['letter'].'</a>';
	   }else{
			$str .='<a href="'.$url.'">'.$arrurl['letter'].'</a>';
	   }
	}
	return $str;
}
//正则提取正文里指定的第几张图片地址
function get_img_url_preg($file,$content,$number=1){
	preg_match_all('/<img(.*?)src="(.*?)(?=")/si',$content,$imgarr);///(?<=img.src=").*?(?=")/si
	preg_match_all('/(?<=src=").*?(?=")/si',implode('" ',$imgarr[0]).'" ',$imgarr);
	$countimg = count($imgarr);
	if($number > $countimg){
		$number = $countimg;
	}
	return $imgarr[0][($number-1)];
}
// 获取某图片的访问地址
function get_img_url($file,$content,$number=1){
	if(!$file){
		return get_img_url_preg($file,$content,$number);
	}
	if(strpos($file,'http://') !== false){
		return $file;
	}
	$prefix = C('upload_ftp_url');
	if(!empty($prefix)){
		return $prefix.$file;
	}else{
		return C('web_path').C('upload_path').'/'.$file;
	}
}
// 获取某图片的缩略图地址
function get_img_url_s($file,$content,$number=1){
	if(!$file){
		return get_img_url_preg($file,$content,$number);
	}
	if(strpos($file,'http://') !== false){
		return $file;
	}	
	$prefix = C('upload_ftp_url');
	if(!empty($prefix)){
		return $prefix.$file;
	}else{
		return C('web_path').C('upload_path').'-s/'.$file;
	}	
}
//集数补零列表
function get_play_name($i,$count){
	if($count>99){
		if($i<10){
			return str_pad($i,3,'0',STR_PAD_LEFT);
		}
		if(10<=$i && $i<100){
			return str_pad($i,3,'0',STR_PAD_LEFT);
		}
	}
	if($count>9 && $i<10){
		return str_pad($i,2,'0',STR_PAD_LEFT);	
	}
	return $i;
}

/**
 * //获取栏目页路径
 * 
 * @param $mid     模型名称'video/info/special'
 * @param $arrurl  为数组参数传入参考U函数(方便动态模式直接生成),只有一个栏目ID参数时 $arrurl['id'] = $cid;
 * @param $page    分页数字,大于1时返回的URL带有分页跳转参数变量{!page!}
 * @return$showurl 栏目页url
 */
function get_show_url($mid,$arrurl,$page){
	//静态模式&栏目静态
	if(C('url_html') && C('url_html_channel') && in_array($mid,array('video','info','special'))){
		$showurl = C('web_path').str_replace('index'.C('html_file_suffix'),'',get_show_url_dir($mid,$arrurl['id'],$page).C('html_file_suffix'));
	}else{//动态模式
		if($page > 1){ $arrurl['p'] = '{!page!}'; }
		$showurl = str_replace('index.php?s=/Home/','index.php?s=',U('Home-'.$mid.'/lists',$arrurl,false,true));
		if(C('url_rewrite')){
			$showurl = str_replace('index.php?s=','',$showurl);
			$showurl = str_replace(array("video/lists/id", "info/lists/id"), array('list', 'newslist'), $showurl);
		}elseif(!C('url_html')){
			$showurl = str_replace('index.php','',$showurl);
		}
	}
	return $showurl;
}

/**
 * //静态生成的栏目结构
 * 生成静态时需要将{!page!}换成对应的page值
 * 
 * @param string  $mid     模型名称'video/info/special'
 * @param int     $cid     当前分类的栏目ID值
 * @param int     $page    分页数字,大于1时返回的URL带有分页跳转参数变量{!page!}
 * @return string $showdir 栏目结构,buildHtml的文件名
 */
function get_show_url_dir($mid,$cid,$page){
	
	//专题直接返回
	if('special' == $mid){
		$showdir = trim(C('url_dir_special')).'/index';
		if($page > 1){
		$showdir .= '{!page!}';
		}
		return $showdir;
	}
	//影视文章静态保存目录
	//结构样式1  /[enname]/id/ 
	if(C('url_html_rule') == 1){
		$listdir = trim(C('url_dir_all'));
		$listdir = !empty($listdir)?$listdir.'/':'';
		$showdir = $listdir.get_channel_name($cid,'cfile').'/index';
		if($page > 1){
		$showdir .= '{!page!}';
		}
		return $showdir;
	}
	//结构样式2 /[dir]/[id] 默认样式
	$showdir = trim(C('url_dir_'.$mid));
	if($showdir){
	$showdir .= '/';
	}
	if(C('url_html_rule') == 2){
		$showdir .= $cid;
		if($page > 1){
		$showdir .= '_{!page!}';
		}
		return $showdir;
	}
	//结构样式3 /[dir]/[enname][id]
	$showdir .= get_channel_name($cid,'cfile');
	if($page > 1){
	$showdir .= '_{!page!}';
	}
	return $showdir;	
}

/**
 * //获取详情页路径
 * 
 * @param $mid    模型名称'video/info/special'
 * @param $id     影片ID/文章ID/专题ID值
 * @param $cid    当前影片/文章/专题/对应的栏目ID值
 * @param $jumpurl跳转地址
 * @param $name   影片/文章/专题/的名称
 * @param $page   分页数字,大于1时返回的URL带有分页跳转参数变量{!page!}
 * @return url    详情页url
 */
function get_read_url($mid,$id,$cid,$jumpurl,$name,$page){
	//有跳转地址
	if ($jumpurl) {
		return $jumpurl;
	}
	//静态
	if(C('url_html')){
		$readurl = C('web_path').str_replace('index'.C('html_file_suffix'),'',get_read_url_dir($mid,$id,$cid,$name,$page).C('html_file_suffix'));
		return $readurl;
	}
	//动态
	$arrurl['id'] = $id;
	$readurl = str_replace('index.php?s=/Home/','index.php?s=',U('Home-'.$mid.'/detail',$arrurl,false,true));
	if(C('url_rewrite')){
		$readurl = str_replace('index.php?s=','',$readurl);
		$readurl = str_replace(array("video/detail/id", "info/detail/id"), array('movie', 'news'), $readurl);
	}else{
		$readurl = str_replace('index.php','',$readurl);
	}
	return $readurl;
}

/**
 * //获取详情页目录,系统设置访问路径中设置
 * 
 * @param $mid      模型名称'video/info/special'
 * @param $id       影片ID/文章ID/专题ID值
 * @param $cid      当前影片/文章/专题/对应的栏目ID值
 * @param $name     影片/文章/专题/的名称
 * @param $page     分页数字,大于1时返回的URL带有分页跳转参数变量{!page!}
 * @return readdir  buildHtml的文件名
 */
function get_read_url_dir($mid,$id,$cid,$name,$page){
	//专题直接返回
	if('special' == $mid){
		return trim(C('url_dir_special').'/'.$id);
	}
	//影视或文章
	//结构样式1
	if(C('url_html_rule') == 1){
		$listdir = trim(C('url_dir_all'));
		$listdir = !empty($listdir)?$listdir.'/':'';	
		$readdir = $listdir.get_channel_name($cid,'cfile').'/'.$id.'/index';
		return $readdir;
	}
	//结构样式2
	$readdir = trim(C('url_dir_'.$mid.'read'));
	$readdir = !empty($readdir)?$readdir.'/':'';
	if(C('url_html_rule') == 2){
		$readdir .= $id;
		return $readdir;
	}
	//结构样式3
	$readdir .= get_channel_name($cid,'cfile').$id;
	return $readdir;		
}
//获取播放页链接
function get_play_url($id,$cid,$ji){
	//静态模式
	if(C('url_html')){
		if(C('url_html_play')){
			$playurl = C('web_path').str_replace('index'.C('html_file_suffix'),'',get_play_url_dir($id,$cid,$ji).C('html_file_suffix'));
			if(C('url_html_play') == 1){
				$playurl .= '?'.$id.'-'.$ji;
			}
		}else{
			$playurl = str_replace('index.php?s=/Home/','index.php?s=',U('Home-video/play/id/'.$id.'-'.$ji));
			if(C('url_rewrite')){
				$playurl = str_replace('index.php?s=','',$playurl);
				$playurl = str_replace("video/play/id","player", $playurl);
			}
		}
	}else{//动态
		$playurl = str_replace('index.php?s=/Home/','index.php?s=',U('Home-video/play/id/'.$id.'-'.$ji));
		if(C('url_rewrite')){
			$playurl = str_replace('index.php?s=','',$playurl);
			$playurl = str_replace("video/play/id","player", $playurl);
		}else{
			$playurl = str_replace('index.php','',$playurl);
		}
	}
	return $playurl;
}
//播放页目录结构
function get_play_url_dir($id,$cid,$ji){
	//结构样式1
	if(C('url_html_rule') == 1){
		$listdir = trim(C('url_dir_all'));
		$listdir = !empty($listdir)?$listdir.'/':'';
		$playdir = $listdir.get_channel_name($cid,'cfile').'/';	
		if(C('url_html_play') == 1){
			return $playdir.$id.'/play';
		}
		return $playdir.$id.'/'.$id.'-'.$ji;
	}
	//结构样式2
	$playdir = trim(C('url_dir_videoplay'));
	$playdir = !empty($playdir)?$playdir.'/':'';
	if(C('url_html_rule') == 2){
		if(C('url_html_play') == 1){
			return $playdir.$id;
		}
		return $playdir.$id.'-'.$ji;		
	}
	//结构样式3
	if(C('url_html_play') == 1){
		return $playdir.get_channel_name($cid,'cfile').$id;
	}
	return $playdir.get_channel_name($cid,'cfile').$id.'-'.$ji;
}
//影视文章专题数据调用
function get_tag_gxcms($tag){
	$table = !empty($tag['name'])?trim($tag['name']):'video';
	$field = !empty($tag['field'])?trim($tag['field']):'*';
	$limit = !empty($tag['limit'])?trim($tag['limit']):'10';
	$order = !empty($tag['order'])?trim($tag['order']):'addtime desc';
	if($table != 'link'){
		$where['status'] = array('eq',1);
	}
	if ($tag['time']) {
		$where['addtime'] = array('gt',xtime($tag['time']));
	}
	if ($tag['hits']) {
		$hits = explode(',',trim($tag['hits']));
		if (count($hits)>1) {
			$where['hits'] = array('between',$hits[0].','.$hits[1]);
		}else{
			$where['hits'] = array('gt',$hits[0]);
		}
	}//根据索引规则放置CID前		
	if ($tag['cid']) {
		$cids = explode(',',trim($tag['cid']));
		if (count($cids)>1) {
			$where['cid'] = array('in',get_channel_remove($cids));
		}else{
			$where['cid'] = get_channel_sqlin($tag['cid']);
		}
	}
	if ($tag['ids']) {
		$where['id'] = array('in',trim($tag['ids']));
	}		
	if ($tag['stars']) {
		$where['stars'] = array('in',trim($tag['stars']));
	}
	if ($tag['letter']) {
		$where['letter'] = array('eq',trim($tag['letter']));
	}
	if ($tag['keyword']) {
		$keyword = trim($tag['keyword']);
		$where['title'] = array('like','%'.$keyword.'%');
	}
	if ($tag['serial'] && $table=='video') {
		$serial = trim($tag['serial']);
		if ('all' == $serial) {
			$where['serial'] = array('neq',0);
		}else{
			$where['serial'] = array('gt',$serial);
		}
	}
	if ($tag['up']) {
		$up = explode(',',trim($tag['up']));
		if (count($up)>1) {
			$where['up'] = array('between',$up[0].','.$up[1]);
		}else{
			$where['up'] = array('gt',$up[0]);
		}
	}
	if ($tag['down']) {
		$down = explode(',',trim($tag['down']));
		if (count($down)>1) {
			$where['down'] = array('between',$down[0].','.$down[1]);
		}else{
			$where['down'] = array('gt',$down[0]);
		}
	}
	if ($tag['score']) {
		$score = explode(',',trim($tag['score']));
		if (count($score)>1) {
			$where['score'] = array('between',$score[0].','.$score[1]);
		}else{
			$where['score'] = array('gt',$score[0]);
		}
	}	
	if ($tag['scoreer']) {
		$scoreer = explode(',',trim($tag['scoreer']));
		if (count($scoreer)>1) {
			$where['scoreer'] = array('between',$scoreer[0].','.$scoreer[1]);
		}else{
			$where['scoreer'] = array('gt',$scoreer[0]);
		}
	}
	$rs = M($table);
	$list = $rs->field($field)->where($where)->order($order)->limit($limit)->select();
	//dump($rs->getLastSql());
	if('special'==$table){
		foreach($list as $key=>$val){
			$list[$key]['readurl'] = get_read_url($table,$list[$key]['id'],$list[$key]['cid']);
			$list[$key]['logo'] = get_img_url($list[$key]['logo']);
			$list[$key]['banner'] = get_img_url_s($list[$key]['banner']);
		}	
	}else{
		foreach($list as $key=>$val){
			$list[$key]['showname'] = get_channel_name($list[$key]['cid']);
			$list[$key]['showurl'] = get_show_url($table,array('id'=>$list[$key]['cid']),1);
			$list[$key]['readurl'] = get_read_url($table,$list[$key]['id'],$list[$key]['cid'],$list[$key]['jumpurl']);
			$list[$key]['playerurl'] = get_play_url($list[$key]['id'],$list[$key]['cid'],1);
			$list[$key]['picurlsmall'] = get_img_url_s($list[$key]['picurl'],$list[$key]['content']);
			$list[$key]['picurl'] = get_img_url($list[$key]['picurl'],$list[$key]['content']);
		}
	}	
	return $list;
}
//影视文章专题栏目分页标签
function get_tag_gxlist($tag){
	//从标签取值
	$table = !empty($tag['name']) ? trim($tag['name']) : 'video';
	$field = !empty($tag['field']) ? trim($tag['field']) : '*';
	$limit = !empty($tag['limit']) ? trim($tag['limit']) : '10';
	$order = !empty($tag['order']) ? trim($tag['order']).' desc' : 'addtime desc';
	$page = C('bdlist_page');//从动态配置文件取值
	//生成查询条件
	if(C('bdlist_where')){
		$where = C('bdlist_where');
	}else{
		if(C('bdlist_ids')){
			$where['cid'] = C('bdlist_ids');//已做大小类范围处理
		}
		$where['status'] = array('eq',1);
	}
	$rs = M($table);
	$list = $rs->field($field)->where($where)->limit($limit)->page($page)->order($order)->select();
	if('special'==$table){
		foreach($list as $key=>$val){
			$list[$key]['readurl'] = get_read_url($table,$list[$key]['id'],$list[$key]['cid']);
			$list[$key]['logo'] = get_img_url($list[$key]['logo']);
			$list[$key]['banner'] = get_img_url_s($list[$key]['banner']);
		}	
	}else{
		foreach($list as $key=>$val){
			$list[$key]['showname'] = get_channel_name($list[$key]['cid']);
			$list[$key]['showurl'] = get_show_url($table,array('id'=>$list[$key]['cid']),1);
			$list[$key]['readurl'] = get_read_url($table,$list[$key]['id'],$list[$key]['cid'],$list[$key]['jumpurl']);
			$list[$key]['playerurl'] = get_play_url($list[$key]['id'],$list[$key]['cid'],1);
			$list[$key]['picurl'] = get_img_url($list[$key]['picurl'],$list[$key]['content']);
			$list[$key]['picurl-s'] = get_img_url_s($list[$key]['picurl'],$list[$key]['content']);
		}
	}
	//dump($rs->getLastSql());
	return $list;
}
//影视文章搜索分页标签
function get_tag_gxsearch($tag){
	$table = !empty($tag['name']) ? trim($tag['name']) : 'video';
	$field = !empty($tag['field']) ? trim($tag['field']) : '*';
	$limit = !empty($tag['limit']) ? trim($tag['limit']) : '10';
	$order = !empty($tag['order']) ? trim($tag['order']).' desc' : 'addtime desc';
	//从动态配置文件取值;
	$page = C('bdsearch_page');
	$where = C('bdsearch_where');
	$rs = M($table);
	$list = $rs->field($field)->where($where)->limit($limit)->page($page)->order($order)->select();
	if (empty($list)) {
		C($table.'empty',true);
	}
	foreach($list as $key=>$val){
		$list[$key]['showname'] = get_channel_name($list[$key]['cid']);
		$list[$key]['showurl'] = get_show_url($table,array('id'=>$list[$key]['cid']),1);
		$list[$key]['readurl'] = get_read_url($table,$list[$key]['id'],$list[$key]['cid'],$list[$key]['jumpurl']);
		$list[$key]['playerurl'] = get_play_url($list[$key]['id'],$list[$key]['cid'],1);
		$list[$key]['picurl'] = get_img_url($list[$key]['picurl'],$list[$key]['content']);
		$list[$key]['picurl-s'] = get_img_url_s($list[$key]['picurl'],$list[$key]['content']);		
	}
	//dump($rs->getLastSql());
	return $list;
}
//全站导航标签
function get_tag_gxmenu($tag){
	$listtree = F('_gxcms/channeltree');
	if ($tag['ids']) {
		$list = F('_gxcms/channel');
		$arrcid = explode(',',trim($tag['ids']));
		foreach($arrcid as $key=>$value){
			$cidvalue = list_search($listtree,'id='.$value);
			if (empty($cidvalue)) {
				$cidvalue = list_search($list,'id='.$value);
			}
			$newtree[$key] = $cidvalue[0];
		}
		$listtree = $newtree;
	}
	return $listtree;
}
// 获取人气值标签
function get_tag_hits($mid,$type='hits',$array,$js=true){
	if((C('url_html') && $js) || $type=='insert'){
		return '<script type="text/javascript" src="'.C('web_path').'index.php?s=hits/show/id/'.$array['id'].'/type/'.$type.'/mid/'.$mid.'" charset="utf-8"></script>';
	}else{
		return $array[$type];
	}
}
//全站路径参数处理函数
function get_url_where(){
	$where['page'] = !empty($_GET['p']) ? intval($_GET['p']) : 1;
	$where['year'] = intval($_REQUEST['year']);
	$where['id'] = intval($_REQUEST['id']);
	$where['letter'] = htmlspecialchars($_REQUEST['letter']);
	$where['area'] = htmlspecialchars(urldecode(trim($_REQUEST['area'])));
	$where['wd'] = htmlspecialchars(urldecode(trim($_REQUEST['wd'])));
	$where['order'] = !empty($_GET['order']) ? get_replace_order($_GET['order']) : 'addtime';
	return $where;
}
//生成字母前缀
function get_letter($s0){
	$firstchar_ord = ord(strtoupper($s0{0})); 
	if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s0{0}; 
	$s = iconv("UTF-8","gb2312", $s0); 
	$asc = ord($s{0})*256+ord($s{1})-65536; 
	if($asc>=-20319 and $asc<=-20284)return "A";
	if($asc>=-20283 and $asc<=-19776)return "B";
	if($asc>=-19775 and $asc<=-19219)return "C";
	if($asc>=-19218 and $asc<=-18711)return "D";
	if($asc>=-18710 and $asc<=-18527)return "E";
	if($asc>=-18526 and $asc<=-18240)return "F";
	if($asc>=-18239 and $asc<=-17923)return "G";
	if($asc>=-17922 and $asc<=-17418)return "H";
	if($asc>=-17417 and $asc<=-16475)return "J";
	if($asc>=-16474 and $asc<=-16213)return "K";
	if($asc>=-16212 and $asc<=-15641)return "L";
	if($asc>=-15640 and $asc<=-15166)return "M";
	if($asc>=-15165 and $asc<=-14923)return "N";
	if($asc>=-14922 and $asc<=-14915)return "O";
	if($asc>=-14914 and $asc<=-14631)return "P";
	if($asc>=-14630 and $asc<=-14150)return "Q";
	if($asc>=-14149 and $asc<=-14091)return "R";
	if($asc>=-14090 and $asc<=-13319)return "S";
	if($asc>=-13318 and $asc<=-12839)return "T";
	if($asc>=-12838 and $asc<=-12557)return "W";
	if($asc>=-12556 and $asc<=-11848)return "X";
	if($asc>=-11847 and $asc<=-11056)return "Y";
	if($asc>=-11055 and $asc<=-10247)return "Z";
	return 0;
}
/************************************************采集核心函数***************************************************************************/
function get_collect_file($url){
	for($i=0;$i<3;$i++){
		$content = @file_get_contents($url);
		if($content) break;
	}
	if($content){
		return $content;
	}
	if(function_exists('curl_init')){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
		$content = curl_exec($ch);
		curl_close($ch);
		if($content){
			return $content;
		}		
	}
	return false;
}
//采集倒序
function get_collect_krsort($listurl){
   krsort($listurl);
   foreach($listurl as $val){
       $list[]=$val;
   }
   return $list;
}
//匹配规则结果
function get_collect_match($rule,$html){
	$arr = explode('$$$',$rule);
	if(count($arr)==2){
	    preg_match('/'.$arr[1].'/', $html, $data);
		return $data[$arr[0]].'';
	}else{
	    preg_match('/'.$rule.'/', $html, $data);
		return $data[1].'';
	}	
}
//匹配规则结果all
function get_collect_matchall($rule,$html){
	$arr = explode('$$$',$rule);
	if(count($arr)==2){
	    preg_match_all('/'.$arr[1].'/', $html, $data);
		return $data[$arr[0]];
	}else{
	    preg_match_all('/'.$rule.'/', $html, $data);
		return $data[1];
	}
}
//规则替换
function getrole($str){
	$arr1 = array('?','"','(',')','[',']','.','/',':','*','||',);
	$arr2 = array('\?','\"','\(','\)','\[','\]','\.','\/','\:','.*?','(.*?)',);
	return str_replace('\[$gxcms\]','([\s\S]*?)',str_replace($arr1,$arr2,$str));
}
//将所有替换规则保存在一个字段
function getreplace($arr){
    foreach($arr as $val){
	    $array[]=trim(stripslashes($val));
	}
	return implode('|||',$array);
}
//获取指定地址的域名
function getdomain($url){
	preg_match("|http://(.*)\/|isU", $url, $arr_domain);
	return $arr_domain[1];
}
//获取绑定分类对应ID值
function getbindval($key){
	$bindcache = F('_collect/channel');
	return $bindcache[$key];
}
//检查是否没有小类
function get_collect_bind($pid){
	$tree = list_search(F('_gxcms/channeltree'),'id='.$pid);
	if(!empty($tree[0]['son'])){
		return false;
	}else{
	    return true;
	}
}
?>