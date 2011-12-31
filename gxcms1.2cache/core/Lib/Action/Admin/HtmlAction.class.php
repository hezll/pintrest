<?php
 /**
 * @name    静态生成管理模块
 * @package GXCMS.Administrator
 * @link    www.gxcms.com
 */
class HtmlAction extends AdminAction{	
	private $VideoDB;	
    private $InfoDB;
    public function _initialize(){
		parent::_initialize();
		$this->assign($this->tags_style());
		$this->VideoDB =D('Home.Video');
		$this->InfoDB  =D('Home.Info');	
	}
	// 生成管理
    public function show(){
		$array['url_html'] = C('url_html');
		if(C('url_html')){
			$array['url_html_channel'] = C('url_html_channel');
		}else{
			$array['url_html_channel'] = 0;
		}
		$this->assign($array);
		$this->assign('list_channel_video',F('_gxcms/channelvideo'));
		$this->assign('list_channel_info',F('_gxcms/channelinfo'));
        $this->display('./views/admin/html_show.html');
    }
	//判断生成条件
    public function checkhtml($midhtml,$errhtml,$gourl='?s=Admin/Html/Show'){	
	    if ($midhtml != 1) {
		    $this->assign("jumpUrl",C('cms_admin').$gourl);
		    $this->error('"'.$errhtml.'"模块动态运行模式,不需要生成静态网页！');
		}
	}
    //生成网站首页
    public function cindex(){
		$this->assign('webtitle',C('web_name'));
		$this->checkhtml(C('url_html'),'网站首页');
		$this->buildHtml("index",'./','Home:index');
		$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show/');
        $this->success('恭喜您,生成网站首页成功。');	    	
    }
	//Maps
    public function maps(){
		$this->maptop(1);
		$this->mapgoogle(1);
		$this->mapbaidu(1);
		$this->maprss(1);
		$go = intval($_GET['go']);
		if ($go) {
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Cindex');
			$this->success('恭喜您！所有地图页已经生成完毕,正在准备生成网站首页!');
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
			$this->success('恭喜您！一键生成所有地图索引页成功！');
		}		
	}	
	//Google地图
    public function mapgoogle($id){
		$googleall = !empty($_REQUEST['googleall'])?intval($_REQUEST['googleall']):500;
		$google    = !empty($_REQUEST['google'])?intval($_REQUEST['google']):100;
		$page      = ceil(intval($googleall)/intval($google));
		for($i=1;$i<=$page;$i++){
			$this->createmap('google',$google,$i);
		}
		if (empty($id)) {
			$this->assign("jumpUrl",'?s=Admin/Html/Show/');
        	$this->success('生成Google Sitemap地图成功,请通过http://www.google.com/webmasters/tools/提交！');
		}			    
    }
	//Baidu地图
    public function mapbaidu($id){
		$baiduall = !empty($_REQUEST['baiduall'])?intval($_REQUEST['baiduall']):500;
		$baidu    = !empty($_REQUEST['baidu'])?intval($_REQUEST['baidu']):100;
		$page     = ceil(intval($baiduall)/intval($baidu));
		for($i=1;$i<=$page;$i++){
			$this->createmap('baidu',$baidu,$i);
		}
		if (empty($id)) {
			$this->assign("jumpUrl",'?s=Admin/Html/Show/');
        	$this->success('生成Baidu Sitemap地图成功！');
		}			    
    }
	//Rss地图
    public function maprss($id){
		$rss = !empty($_REQUEST['rss'])?intval($_REQUEST['rss']):50;
		$this->createmap('rss',$rss,1);
		if (empty($id)) {
			$this->assign("jumpUrl",'?s=Admin/Html/Show/');
        	$this->success('Rss订阅文件生成成功！');
		}
    }	
	//生成地图
    public function createmap($mapname,$limit,$page){
		$suffix = C('html_file_suffix');
		$rs = D("Admin.Video");
		$list = $rs->order('addtime desc')->limit($limit)->page($page)->select();
		foreach($list as $key=>$val){
			$list[$key]['readurl'] = get_read_url('video',$val['id'],$val['cid']);
			$list[$key]['playurl'] = get_play_url($val['id'],$val['cid'],1);
		}	
		$this->assign('listmap',$list);
		C('html_file_suffix','.xml');
		if ($page == 1){
			$this->buildHtml($mapname,'./'.C('url_dir_maps').'/','./views/xml/'.$mapname.'.html');
		}else{
			$this->buildHtml($mapname.'-'.$page,'./'.C('url_dir_maps').'/','./views/xml/'.$mapname.'.html');
		}
		C('html_file_suffix',$suffix);
    }
	//生成排行榜单
    public function maptop($id){
		$this->assign('webtitle','排行榜'.'-'.C('web_name'));
		$this->assign('navtitle','<a href="'.C('web_path').'">首页</a> &gt; <span>排行榜</span>');
		$this->buildHtml('top10','./'.C('url_dir_maps').'/','./template/'.C('default_theme').'/Home/top10.html');
		if (empty($id)) {
			$this->assign("jumpUrl",'?s=Admin/Html/Show/');
        	$this->success('排行榜单生成成功！');
		}		    
    }
	//生成影视列表
    public function videoshow(){
		$msid = intval($_REQUEST['msid']);//用户选择的分类ID
		$key  = intval($_GET['key']);//当前第几个
		$go   = intval($_GET['go']);//是否跳转到下一个
		$this->checkhtml(C('url_html_channel'),'影视栏目','?s=Admin/Html/Inforead/go/'.$go);
		$cid = $msid;//当前需要生成的栏目ID
		$cid_count = 1;//此次任务共需要生成多少个栏目
		if($msid < 1){//如果为生成全部分类选项,则计算出当前的CID值与共生成多少个栏目
			$rs = M("Channel");
			$arr = $rs->field('id,ctpl')->where('mid = 1')->select();
			$cid = $arr[$key]['id'];
			$cid_count = count($arr);
		}
		$list = list_search(F('_gxcms/channel'),'id='.$cid);$channel = $list[0];//获取当前栏目的缓存信息
		//查询本类及小类共多少条数据
		$rs = M("Video");
		$where['status'] = array('eq',1);
		if(get_channel_son($cid)){
			$where['cid'] = $cid;
		}else{
			$where['cid'] = get_channel_sqlin($cid);
		}
		$count = $rs->where($where)->count('id');	
		//计算出该栏目需要生成的总页数
		$totalpages = ceil($count/$channel['limit']);
		if ($totalpages < 1) {
			$totalpages = 1;
		}
		//准备生成
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		echo'<li>总共需要生成<span>'.$cid_count.'</span>个栏目,当前栏目共需要生成<span>'.$totalpages.'</span>页</li>';
		for ($ii = 1; $ii<=$totalpages; $ii++) {
			//当前栏目前台标签数组变量-----------------------------------------------------------------------------------
			$pageurl= get_show_url('video',array('id'=>$cid),2);
			$pages  = '共'.$count.'部影片&nbsp;当前:'.$ii.'/'.$totalpages.'页&nbsp;';
			$pages .= get_cms_page_css($ii,$totalpages,5,$pageurl,false);
			$channel['cid']  = $cid;
			$channel['page'] = $ii;
			$channel['count']= $count;
			$channel['pages']= $pages;
			if ($ii > 1) {
				$channel['webtitle'] = $channel['cname'].'-第'.$ii.'页-'.C('web_name');
			}else{	
				$channel['webtitle'] = $channel['cname'].'-'.C('web_name');
			}
			if($channel['pid']){
				$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <a href="'.$channel['showurl_p'].'">'.$channel['cname_p'].'</a> &gt; <span>'.$channel['cname'].'</span>';
			}else{
				$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <span>'.$channel['cname'].'</span>';	
			}
			//先给bdlist标签传值后再生成模板
			C('bdlist_ids',$where['cid']);
			C('bdlist_page',$ii);
			//生成静态网页开始	
			if (empty($channel['ctpl'])) {
				$channel['ctpl'] = 'video_list';
			}			
			$this->assign($channel);			
			$listdir = str_replace('{!page!}',$ii,get_show_url_dir('video',$cid,$ii));//目录路径
			$showurl = C('webpath').$listdir.C('html_file_suffix');//预览路径	
			$this->buildHtml($listdir,'./','Home:'.$channel['ctpl']);
			echo'<li>第'.($key+1).'个栏目 第<span>'.$ii.'</span>页 <a href="'.$showurl.'" target="_blank">'.$showurl.'</a> 操作成功</li>';
			ob_flush();
			flush();
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		//栏目个数是否生成完成
		if (($key+1) < $cid_count) {
			$this->assign("waitSecond",C('url_create_time'));
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Videoshow/msid/'.$msid.'/key/'.($key+1).'/go/'.$go);
			$this->success('第'.($key+1).'个影视栏目已经生成完毕,正在准备下一个,请稍等！');
		}else{
			if ($go) {
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Inforead/go/'.$go);
				$this->success('恭喜您!影视栏目页已经生成完毕,正在准备生成新闻内容页，请稍等！');
			}else{
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
				$this->success('恭喜您!您所选择的栏目页已经生成完毕！');				    
			}
		}			    
    }
	//生成影视内容按分类
    public function videoread(){
		$mrid = intval($_REQUEST["mrid"]);
		$go   = intval($_GET['go']);	
		$this->checkhtml(C('url_html'),'影视内容','?s=Admin/Html/Videoshow/msid/0/go/'.$go);
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		if($mrid){
			if(get_channel_son($mrid)){
				$where['cid'] = $mrid;
			}else{
				$where['cid'] = get_channel_sqlin($mrid);
			}	
		}
		$where['status'] = 1;
		$count      = $this->VideoDB->where($where)->count('id');
		$listRows   = C('url_create_num');//每页生成数
		$totalpages = ceil($count/$listRows);//总生成页数
		$nowpage = !empty($_GET['page'])?intval($_GET['page']):1;$nowpage = get_cms_page_max($count,$listRows,$nowpage);//当前页数
		echo'<li>总共需要生成<span>'.$count.'</span>部影片,每页生成<span>'.$listRows.'</span>部,共需要分<span>'.$totalpages.'</span>页生成,当前正在生成第<span>'.$nowpage.'</span>页。</li>';
		$list = $this->VideoDB->where($where)->order('addtime desc')->limit($listRows)->page($nowpage)->select();
		if (empty($list)) {
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
			$this->error('暂无数据不需要生成！');
		}
		foreach($list as $key=>$value){
			$this->createvideo($value);
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		$this->assign("waitSecond",C('url_create_time'));
		if ($nowpage < $totalpages) {//是否生成完成
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Videoread/mrid/'.$mrid.'/page/'.($nowpage+1).'/go/'.$go);
			$this->success('第('.$nowpage.')页已经完成,正在准备下一个,请稍等！');
		}else{
			if ($go) {
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Videoshow/msid/0/go/'.$go);
				$this->success('恭喜您！所有影视内容页已经生成完毕，正在准备生成影视栏目页，请稍等！');
			}else{
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
				$this->success('恭喜您！所有影视内容页已经生成完毕！');				    
			}
		}
	}
	//生成影视内容按日期
    public function videoday(){
		$this->checkhtml(C('url_html'),'影视内容');
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		$mday             = intval($_REQUEST['mday']);
		$where['status']  = array('eq',1);
		$where['addtime'] = array('gt',xtime($mday));
		$count = $this->VideoDB->where($where)->count('id');
		$listRows   = C('url_create_num');
		$totalpages = ceil($count/$listRows);
		$nowpage    = !empty($_GET['page'])?$_GET['page']:1;
		$nowpage    = intval($nowpage);
		$list = $this->VideoDB->where($where)->order('addtime desc')->limit($listRows)->page($nowpage)->select();
		if (empty($list)) {
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
			$this->error('今日没有更新数据,暂不需要生成！');
		}
		foreach($list as $key=>$value){
			$this->createvideo($value);
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		if ($nowpage<$totalpages) {
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Videoday/page/'.($nowpage+1).'/mday/'.$mday);
			$this->success('第('.$nowpage.')页已经完成,正在准备下一个,请稍等！');
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Videoshow/msid/0/go/1');
			$this->success('内容页已经生成完毕,正在准备生成影视栏目页,请稍等!');	
		}
	}
	//生成影视内容按ID
    public function videoid(){
		$this->checkhtml(C('url_html'),'影视内容');
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		$ids = $_GET['ids'];
		$where['status'] = array('eq',1);
		$where['id']     = array('in',$ids);
		$list = $this->VideoDB->where($where)->select();
		foreach($list as $key=>$value){
			$this->createvideo($value);
		}
		echo '<li>恭喜您！所选择的影片已经生成完毕</li></div>';
	}	
	//生成视频内容
    public function createvideo($array){
		import("ORG.Io.Dir");
		$dir = new Dir;
		$arrays = $this->tags_video_read($array);//变量赋值
		$this->assign($arrays['show']);
		$this->assign($arrays['read']);
		$videodir = get_read_url_dir('video',$arrays['read']['id'],$arrays['read']['cid']);//保存路径
		$this->buildHtml($videodir,'./','Home:video_detail');//生成视频内容页
		$videourl = C('webpath').$videodir.C('html_file_suffix');//预览路径
		echo '<li>'.$array['id'].'的内容页 <a href="'.$videourl.'" target="_blank">'.$videourl.'</a> 操作成功</li>';
		//是否生成播放页
		if(C('url_html') && C('url_html_play')){
			if(C('url_html_play') == 1){
				$this->createplay($array,0);
			}else{
				for($i=1;$i<=$arrays['read']['count'];$i++){			
					$this->createplay($array,$i);
				}
			}
		}
		ob_flush();flush();
	}
	//生成视频播放页
    public function createplay($array,$ji){
		$arrays = $this->tags_video_read($array,array($array['id'],$ji));//变量赋值
		$this->assign($arrays['show']);
		$this->assign($arrays['read']);
		$playdir = get_play_url_dir($array['id'],$array['cid'],$ji);//保存路径
		$this->buildHtml($playdir,'./','Home:video_play');//生成视频播放页
		$playurl = C('webpath').$playdir.C('html_file_suffix');//预览路径
		echo '<li style="color:#666">'.$array['id'].'的播放页 <a href="'.$playurl.'" target="_blank">'.$playurl.'</a> 操作成功</li>';
	}	
	//生成文章列表
    public function infoshow(){
		$asid = intval($_REQUEST['asid']);//用户选择的分类ID
		$key  = intval($_GET['key']);//当前第几个
		$go   = intval($_GET['go']);//是否跳转到下一个
		$this->checkhtml(C('url_html_channel'),'文章栏目','?s=Admin/Html/Specialread/go/'.$go);
		$cid  = $asid;//当前需要生成的栏目ID
		$cid_count = 1;//此次任务共需要生成多少个栏目
		if($asid < 1){//如果为生成全部分类选项,则计算出当前的CID值与共生成多少个栏目
			$rs = M("Channel");
			$arr = $rs->field('id,ctpl')->where('mid = 2')->select();
			$cid = $arr[$key]['id'];
			$cid_count = count($arr);
		}
		$list = list_search(F('_gxcms/channel'),'id='.$cid);$channel = $list[0];//获取当前栏目的缓存信息
		//查询本类及小类共多少条数据
		$rs = M("Info");
		$where['status']  = array('eq',1);
		if(get_channel_son($cid)){
			$where['cid'] = $cid;
		}else{
			$where['cid'] = get_channel_sqlin($cid);
		}
		$count = $rs->where($where)->count('id');	
		//计算出该栏目需要生成的总页数
		$totalpages = ceil($count/$channel['limit']);
		if ($totalpages < 1) {
			$totalpages = 1;
		}
		//准备生成
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		echo'<li>总共需要生成<span>'.$cid_count.'</span>个栏目,当前栏目共需要生成<span>'.$totalpages.'</span>页</li>';
		for ($ii = 1; $ii<=$totalpages; $ii++) {
			//当前栏目前台标签数组变量-----------------------------------------------------------------------------------
			$pageurl = get_show_url('info',array('id'=>$cid),2);
			$pages = '共'.$count.'篇资讯&nbsp;当前:'.$ii.'/'.$totalpages.'页&nbsp;';
			$pages .= get_cms_page_css($ii,$totalpages,5,$pageurl,false);
			$channel['cid'] = $cid;
			$channel['page'] = $ii;
			$channel['pages'] = $pages;
			$channel['count'] = $count;
			if ($ii > 1) {
				$channel['webtitle'] = $channel['cname'].'-第'.$ii.'页-'.C('web_name');
			}else{	
				$channel['webtitle'] = $channel['cname'].'-'.C('web_name');
			}
			if($channel['pid']){
				$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <a href="'.$channel['showurl_p'].'">'.$channel['cname_p'].'</a> &gt; <span>'.$channel['cname'].'</span>';
			}else{
				$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <span>'.$channel['cname'].'</span>';	
			}
			//先给bdlist标签传值后再生成模板
			C('bdlist_ids',$where['cid']);
			C('bdlist_limit',$channel['limit']);
			C('bdlist_page',$ii);	
			//生成静态网页开始	
			if (empty($channel['ctpl'])) {
				$channel['ctpl'] = 'info_list';
			}			
			$this->assign($channel);			
			$listdir = str_replace('{!page!}',$ii,get_show_url_dir('info',$cid,$ii));//目录路径
			$showurl = C('webpath').$listdir.C('html_file_suffix');//预览路径
			$this->buildHtml($listdir,'./','Home:'.$channel['ctpl']);
			echo'<li>第'.($key+1).'个栏目 第<span>'.$ii.'</span>页 <a href="'.$showurl.'" target="_blank">'.$showurl.'</a> 操作成功</li>';
			ob_flush();
			flush();
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		//栏目列表是否生成完成
		if (($key+1) < $cid_count) {
			$this->assign("waitSecond",C('url_create_time'));
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Infoshow/asid/'.$asid.'/key/'.($key+1).'/go/'.$go);
			$this->success('第'.($key+1).'个文章栏目已经生成完毕,正在准备下一个,请稍等！');
		}else{
			if($go){
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Specialread/go/'.$go);
				$this->success('恭喜您,文章栏目页已经生成完毕,正在准备生成专题内容页，请稍等！');
			}else{
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
				$this->success('恭喜您,您所选择的栏目页已经生成完毕！');				    
			}
		}			    
    }
	//生成文章内容按分类
    public function inforead(){
		$go   = intval($_GET['go']);
		$arid = intval($_REQUEST["arid"]);
		$this->checkhtml(C('url_html'),'文章内容','?s=Admin/Html/Infoshow/asid/0/go/'.$go);
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		$rs = M("Info");
		$where['status'] = 1;
		if($arid){
			if(get_channel_son($arid)){
				$where['cid'] = $arid;
			}else{
				$where['cid'] = get_channel_sqlin($arid);
			}	
		}
		$count = $rs->where($where)->count('id');
		$listRows   = C('url_create_num');//每页生成数
		$totalpages = ceil($count/$listRows);//总生成页数
		//当前页数
		$nowpage = !empty($_GET['page'])?$_GET['page']:1;
		$nowpage = intval($nowpage);
		if (!empty($totalpages) && $nowpage>$totalpages) { 
			$nowpage = $totalpages; 
		}
		echo'<li>总共需要生成<span>'.$count.'</span>篇文章,每页生成<span>'.$listRows.'</span>篇,共需要分<span>'.$totalpages.'</span>页生成,当前正在生成第<span>'.$nowpage.'</span>页。</li>';
		$list = $rs->where($where)->order('addtime desc')->limit($listRows)->page($nowpage)->select();
		foreach($list as $key=>$value){
			$this->createinfo($value);
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		if ($nowpage<$totalpages) {//是否生成完成
			$this->assign("waitSecond",C('url_create_time'));
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Inforead/arid/'.$arid.'/page/'.($nowpage+1).'/go/'.$go);
			$this->success('第('.$nowpage.')页已经完成,正在准备下一个,请稍等！');
		}else{
			if ($go) {
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Infoshow/asid/0/go/'.$go);
				$this->success('恭喜您！所有新闻内容页已经生成完毕,正在准备生成新闻栏目页，请稍等！');
			}else{
				$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
				$this->success('恭喜您！所有静态化网页的任务已经完成！');				    
			}
		}
	}	
	//生成新闻当天
    public function infoday(){
		$this->checkhtml(C('url_html'),'文章内容');
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		$aday = intval($_REQUEST['aday']);
		$where['status'] = array('eq',1);
		$where['addtime']= array('gt',xtime($aday));
		$count = $this->InfoDB->where($where)->count('id');
		$listRows   = C('url_create_num');
		$totalpages = ceil($count/$listRows);
		$nowpage    = !empty($_GET['page'])?$_GET['page']:1;
		$nowpage    = intval($nowpage);
		$list = $this->InfoDB->where($where)->order('addtime desc')->limit($listRows)->page($nowpage)->select();
		if (empty($list)) {
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
			$this->error('今日没有更新数据,暂不需要生成！');
		}
		foreach($list as $key=>$value){
			$this->createinfo($value);
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		if ($nowpage<$totalpages) {//是否生成完成
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Infoday/page/'.($nowpage+1).'/aday/'.$aday);
			$this->success('第('.$nowpage.')页已经完成,正在准备下一个,请稍等！');
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Infoshow/asid/0');
			$this->success('恭喜您！今日更新的新闻资讯已经生成完毕！正在准备生成新闻栏目页,请稍等!');	
		}
	}
	//生成新闻按ID
    public function infoid(){
		$this->checkhtml(C('url_html'),'文章内容');
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		$ids = $_GET['ids'];
		$where['status'] = array('eq',1);
		$where['id']     = array('in',$ids);
		$list = $this->InfoDB->where($where)->select();
		foreach($list as $key=>$value){
			$this->createinfo($value);
		}
		echo '<li>恭喜您！所选择的文章已经生成完毕</li></div>';
	}	
	//生成新闻页
    public function createinfo($array){
		if($array['jumpurl']){ break; }//跳转地址不生成
		$arrays = $this->tags_info_read($array);//变量赋值
		$this->assign($arrays['show']);
		$this->assign($arrays['read']);
		$infodir = get_read_url_dir('info',$arrays['read']['id'],$arrays['read']['cid']);//保存路径
		$this->buildHtml($infodir,'./','Home:info_detail');
		$infourl = C('webpath').$infodir.C('html_file_suffix');
		echo '<li>ID为'.$array['id'].'的文章内容页生成完毕 <a href="'.$infourl.'" target="_blank">'.$infourl.'</a></li>';
		ob_flush();
		flush();
	}
	//生成专题列表
    public function specialshow(){
		$go = intval($_GET['go']);
		$this->checkhtml(C('url_html_channel'),'专题栏目','?s=Admin/Html/Maps/go/'.$go);
		$list = F('_gxcms/channel');$channel['limit'] = $list[999]['special'];
		//查询本类及小类共多少条数据
		$rs = M("Special");
		$where['status'] = array('eq',1);
		$count = $rs->where($where)->count('id');
		//计算出该栏目需要生成的总页数
		$totalpages = ceil($count/$channel['limit']);
		if ($totalpages < 1) {
			$totalpages = 1;
		}
		//准备生成
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		echo'<li>网站专题共需要生成<span>'.$totalpages.'</span>页</li>';
		for ($ii=1; $ii<=$totalpages; $ii++) {	
			//当前专题分页前台标签数组变量-------------------------------------------------------------------
			$pageurl= get_show_url('special',array('id'=>$cid),2);
			$pages  = '共'.$count.'篇专题&nbsp;当前:'.$ii.'/'.$totalpages.'页&nbsp;';
			$pages .= get_cms_page_css($ii,$totalpages,5,$pageurl,false);
			$channel['page']  = $ii;
			$channel['pages'] = $pages;
			$channel['count'] = $count;
			if ($ii > 1) {
				$channel['webtitle'] = '专题列表-第'.$ii.'页-'.C('web_name');
			}else{	
				$channel['webtitle'] = '专题列表-'.C('web_name');
			}
			$channel['navtitle'] = '<a href="'.C('web_path').'">首页</a> &gt; <span>专题列表</span>';
			//先给bdlist标签传值后再生成模板
			C('bdlist_page',$page);	
			$this->assign($channel);		
			$listdir = str_replace('{!page!}',$ii,get_show_url_dir('special',$cid,$ii));//保存路径
			$this->buildHtml($listdir,'./','Home:special_list');
			$specialurl = C('webpath').$listdir.C('html_file_suffix');//预览路径
			echo'<li>专题列表第<span>'.$ii.'</span>页 <a href="'.$specialurl.'" target="_blank">'.$specialurl.'</a> 操作成功</li>';			
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		if($go){
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Maps/go/'.$go);
			$this->success('专题列表已经生成完毕,正在准备生成网站地图，请稍等！');
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
			$this->success('恭喜您,专题列表页已经生成完毕！');				    
		}		    
    }
	//生成专题内容
    public function specialread(){
		$go = intval($_GET['go']);
		$this->checkhtml(C('url_html'),'专题内容','?s=Admin/Html/Specialshow/go/'.$go);
		echo'<div class="htmllist" id="show" style="font-size:12px;">';
		$rs = M("Special");
		$list = $rs->order('addtime desc')->select();
		foreach($list as $key=>$value){
			$array = $this->tags_special_read($value);//变量赋值
			$this->assign($array);
			$specialdir = get_read_url_dir('special',$array['id']);
			$this->buildHtml($specialdir,'./','Home:special_detail');
			$specialurl = C('webpath').$specialdir.C('html_file_suffix');
			echo '<li>ID为'.$arrays['read']['id'].'的专题内容页生成完毕 <a href="'.$specialurl.'" target="_blank">'.$specialurl.'</a></li>';
		}
		echo '</div><script>document.getElementById("show").style.display="none";</script>';
		if ($go) {
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Specialshow/go/'.$go);
			$this->success('专题内容已经生成完毕,正在准备生成专题栏目,请稍等!');
		}else{
			$this->assign("jumpUrl",C('cms_admin').'?s=Admin/Html/Show');
			$this->success('恭喜您,所有专题内容已经生成完毕！');
		}		    
    }	
}
?>