<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($webtitle); ?></title>
<meta name="keywords" content="<?php echo ($ckeywords); ?><?php echo ($keywords); ?>">
<meta name="description" content="<?php echo ($cdescription); ?><?php echo ($description); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<script language="javascript">var SitePath='<?php echo ($webpath); ?>';var SiteMid='<?php echo ($mid); ?>';var SiteCid='<?php echo ($cid); ?>';var SiteId='<?php echo ($id); ?>';</script>
<script language="JavaScript" type="text/javascript" src="<?php echo ($webpath); ?>views/js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo ($webpath); ?>views/js/system.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo ($webpath); ?>views/js/history.js"></script>
<link rel='stylesheet' type='text/css' href='<?php echo ($webpath); ?>views/css/system.css'>
<link rel='stylesheet' type='text/css' href='<?php echo ($tplpath); ?>template.css'>
</head>
<body>
<div id="wrapper">
<script language="JavaScript" type="text/javascript" src="<?php echo ($tplpath); ?>template.js"></script>
<div class="header">
  <div class="top">
    <div title="<?php echo ($webname); ?>" class="logo"></div>
    <?php if(C('user_register') == 1): ?><div id="Login" class="login"></div><?php endif; ?>
    <div class="control"><a href="<?php echo ($rssurl); ?>">RSS订阅</a>&nbsp;|&nbsp;<a href="javascript:void(0)" id="fav">收藏本站</a>&nbsp;|&nbsp;<a href="<?php echo ($guestbookurl); ?>">留言反馈</a>&nbsp;|&nbsp;<span class="his"  id="ggg" onmouseover="fnDisplayMenu(this,'mnuArtStyles');" onmouseout="fnHideMenu('mnuArtStyles'); fnRemoveHighlight('mnuArtStyles');" ><a class="headerMnuLink" id="mnuArtStylesLink" href="javascript:void(0);">播放记录</a></span></div>

    <div class="popup1" id="mnuArtStyles"  style="display:none" onmouseover="fnDisplayMenu2($('#mnuArtStylesLink'),'mnuArtStyles');" onmouseout="fnHideMenu('mnuArtStyles'); fnRemoveHighlight('mnuArtStyles');" >
      <div id="history">
      </div>
   </div>

  </div>
  <div class="nav"><a href="<?php echo ($webpath); ?>" <?php if($model == 'index'): ?>class="cur"<?php endif; ?>>首页</a>
    <?php $tag['name'] = 'menu'; $__LIST__ = get_tag_gxmenu($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): ++$i;$mod = ($i % 2 );?><a onfocus="this.blur();" href="<?php echo ($menu["showurl"]); ?>" <?php if(($menu['id'] == $cid) or ($menu['id'] == $pid)): ?>class="cur"<?php endif; ?>><?php echo ($menu["cname"]); ?></a><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?><span>|</span>
    <a href="<?php echo ($topurl); ?>" <?php if(check_model('top10') == 1): ?>class="cur"<?php endif; ?>>排行</a>
    <a href="<?php echo ($specialurl); ?>" <?php if(check_model('special') == 1): ?>class="cur"<?php endif; ?>>专题</a>


   
  </div>
  <div class="query"> <span class="query_l"></span>
    <form action="<?php echo ($webpath); ?>index.php?s=video/search" method="post" name="search" id="search" >
      <input type="text" value="<?php echo (($keyword)?($keyword):'请输入关键字'); ?>" id="wd" name="wd" autocomplete="off" maxlength="35">
      <div id="search_sort"><span id="cur_txt">视频</span><ul class="sort" id="sort"><li><a href="javascript:void(0)">视频</a></li><li><a href="javascript:void(0)">新闻</a></li></ul></div>
      <button type="submit" class="search_bt"><span>搜索</span></button>
    </form>
    <div class="hot_kw">热门关键词：<?php echo ($hotkey); ?></div>
    <span class="query_r"></span> </div>
</div>
<div class="box"><span>您现在所在的位置：</span><?php echo ($navtitle); ?></div>
<div class="blank"></div>
<div class="mov_detail_box">
  <div class="mov_detail_ad"><?php echo get_cms_ads('right-250250');?></div>
  <div class="mov_detail">
    <div class="bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="mov_detail_intro">
          <p class="pic"><a href="<?php echo ($playurl_first); ?>"><img src="<?php echo ($picurl); ?>" title="<?php echo ($video["title"]); ?>" onerror="this.src='<?php echo ($webpath); ?>views/images/nophoto.jpg'" width="119" height="170" border="0"/></a></p>
          <?php if(c(url_html) == 1): ?><p class="score"><span id="Scorenum" class="Scorenum"><?php echo ($score); ?></span>分 （<span id="Scoreer" class="Scoreer"><?php echo ($scoreer); ?></span>人评价过此片）</p>
          <?php else: ?>
          <p class="score"><span id="Scorenum"><?php echo ($score); ?></span>分 （<span id="Scoreer"><?php echo ($scoreer); ?></span>人评价过此片）</p><?php endif; ?>
          <p class="intro"><a href="<?php echo ($readurl); ?>" class="title" title="<?php echo ($title); ?>"><?php echo ($title); ?></a><span class="intro"><?php if(!empty($intro)): ?><?php echo ($intro); ?><?php endif; ?> <?php if(in_array(($cid), explode(',',"3,4,15,16,17,18,19,20,21,22"))): ?><?php if(!empty($serial)): ?>连载至<?php echo ($serial); ?><?php if(($pid)  ==  "4"): ?>期<?php else: ?>集<?php endif; ?><?php endif; ?></span><?php endif; ?></p>
          <p class="actor">主演：<?php if(!empty($actor)): ?><?php echo (get_actor_url($actor)); ?><?php else: ?>未知<?php endif; ?><br />
            <span class="wide">导演：<?php if(!empty($director)): ?><?php echo (get_actor_url($director)); ?><?php else: ?>未知<?php endif; ?></span><span class="wide">影片类型：<a href="<?php echo ($showurl); ?>"><?php echo ($cname); ?></a> </span><br />
            <span class="wide">地区：<?php echo (($area)?($area):'未知'); ?></span><span class="wide">上映时间：<?php echo (($year)?($year):'未录入'); ?></span><br />
            <span class="wide">语言：<?php echo (($language)?($language):'未知'); ?></span><span class="wide">更新时间：<?php echo (get_color_date('Y-m-d',$addtime)); ?></span><br />
          </p>
          <p class="bar"> <?php if(C('user_comment') == 1): ?><a href="#comment" class="com_btn">发表评论</a><?php endif; ?>&nbsp;</p>
          <p><a href="<?php echo ($playurl_first); ?>" class="view_btn"><span>立即观看</span></a></p>
          <div id="ckepop" class="jia"><a class="jiathis_button_baidu"></a><a class="jiathis_button_tsina"></a><a class="jiathis_button_tqq"></a><a class="jiathis_button_kaixin001"></a><a class="jiathis_button_renren"></a></div>
        </div>
      </div>
      <span class="bl"></span><span class="br"></span></div>
  </div>
</div>
<div class="blank"></div>
<div class="mov_detail_box bd"><span class="tl"></span><span class="tr"></span>
  <div class="ct">
    <div class="hd"><h3>影片摘要</h3></div>
    <?php if(($count)  >  "1"): ?><div class="play_list">
      <p class="title">播放列表：<span class="tip">(点击单集开始播放)</span></p>
      <div id="plist">
        <ul class="split-list">
          <?php if(is_array($playlist)): $i = 0; $__LIST__ = $playlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 )?><li><a href="<?php echo ($video["playurl"]); ?>" target="_blank">[<?php echo ($video["playname"]); ?>]</a></li>
          <?php if(($i)  ==  "54"): ?></ul><ul id="all-plist" class="split_list" style="display:none;"><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <?php if(($count)  >  "54"): ?><div id="pmoreContain" class="play-list-right"><a onfocus="this.blur()" id="plMore">展开列表</a></div><?php endif; ?>
      </div>
    </div><?php endif; ?>
    <div class="brief_info">
      <div class="title">剧情介绍：</div>
      <div class="brief_info_cont"> <?php echo (htmlspecialchars_decode($content)); ?> </div>
    </div>
    <div class="rt_recom">
      <div class="title">同类热门推荐：</div>
      <ul class="mov_pic_list">
      	<span id="hot_video" href="<?php echo ($webpath); ?>index.php?s=my/show/id/hot_video/cid/<?php echo ($cid); ?>/limit/7">Loading...</span>
      </ul>
    </div>
  </div>
  <span class="bl"></span><span class="br"></span> </div>
<div class="blank"></div>

<?php if(C('user_comment') == 1): ?><div class="mov_detail_box bd"><span class="tl"></span><span class="tr"></span>
  <div class="ct">
  	<div class="hd"><h3>影片评论</h3></div><a name="comment" id="comment"></a>
    <div id="Comments"></div>
  </div>
  <span class="bl"></span><span class="br"></span> </div><?php endif; ?>
<script type="text/javascript" src="http://v1.jiathis.com/code/jia.js" charset="utf-8"></script>
<div id="footer">
	<?php echo ($copyright); ?><br />
	Copyright © 2007 - 2011 <a href="<?php echo ($weburl); ?>"><?php echo ($webname); ?></a> Some Rights Reserved <?php echo ($icp); ?> <?php echo ($tongji); ?> <a href="<?php echo ($baidusitemap); ?>">sitemap</a> <a href="<?php echo ($googlesitemap); ?>">sitemap</a><br />
</div>
</body>
</html>