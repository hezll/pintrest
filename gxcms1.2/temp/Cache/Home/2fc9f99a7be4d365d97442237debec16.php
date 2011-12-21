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
<div class="box">
  <span>您现在所在的位置：</span><?php echo ($navtitle); ?>
</div>
<div class="box">
  <div class="left_col">
    <!--热门电影排行 开始-->
    <div class="topbrd"></div>
    <div class="bd"><div class="ct">
        <div class="hd"><h3>热门<?php echo ($cname); ?>排行</h3><a href="<?php echo ($topurl); ?>" class="more">更多&gt;&gt;</a></div>
        <ul class="top"><?php $tag['name'] = 'video';$tag['cid'] = ''.$cid.'';$tag['limit'] = '10';$tag['order'] = 'weekhits desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li <?php if(($i)  >=  "4"): ?>class="b <?php if($i == 10): ?>nobrd<?php endif; ?>"<?php endif; ?>> 
        	<em><?php echo ($i); ?></em><a href="<?php echo ($video["readurl"]); ?>" target="_blank" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,15)); ?></a> <span><?php echo (get_color_date('m-d',$video["addtime"])); ?></span>
        </li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?></ul>
    </div></div>
    <div class="btmbrd"></div>
    <!--热门电影排行 结束-->
    <div class="blank"></div>
    <!--网友最爱电影排行 开始-->
    <div class="topbrd"></div>
    <div class="bd"><div class="ct">
        <div class="hd"><h3>网友最爱<?php echo ($cname); ?></h3><a href="<?php echo ($topurl); ?>" class="more">更多&gt;&gt;</a></div>
        <ul class="top"><?php $tag['name'] = 'video';$tag['cid'] = ''.$cid.'';$tag['limit'] = '10';$tag['order'] = 'score desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li <?php if(($i)  >=  "4"): ?>class="b <?php if($i == 10): ?>nobrd<?php endif; ?>"<?php endif; ?>> 
        	<em><?php echo ($i); ?></em><a href="<?php echo ($video["readurl"]); ?>" target="_blank" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,15)); ?></a> <?php if(($i)  <  "4"): ?><span style="color:#e02e2e;"><?php else: ?><span><?php endif; ?><?php echo ($video["score"]); ?></span>
        </li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?></ul>
      </div>
    </div>
    <div class="btmbrd"></div>
    <!--网友最爱电影排行 结束-->
  </div>
  <div class="right_col">
    <div class="mov bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="hd">
          <h3>影片检索：</h3>
        </div>
        <div class="mov_index"><?php if((get_channel_son($cid) == 0) or ($pid > 0)): ?><dl>
            <dt>按类型</dt>
            <dd>
            <?php if(($pid)  >  "0"): ?><?php $tag['name'] = 'menu';$tag['ids'] = ''.$pid.''; $__LIST__ = get_tag_gxmenu($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): ++$i;$mod = ($i % 2 );?><?php if(is_array($menu["son"])): $i = 0; $__LIST__ = $menu["son"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menuson): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($menuson["showurl"]); ?>" <?php if(($cid)  ==  $menuson["id"]): ?>class="Class"<?php endif; ?>><?php echo ($menuson["cname"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
           <?php else: ?>
		   <?php $tag['name'] = 'menu';$tag['ids'] = ''.$cid.''; $__LIST__ = get_tag_gxmenu($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): ++$i;$mod = ($i % 2 );?><?php if(is_array($menu["son"])): $i = 0; $__LIST__ = $menu["son"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menuson): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($menuson["showurl"]); ?>" <?php if(($cid)  ==  $menuson["id"]): ?>class="Class"<?php endif; ?>><?php echo ($menuson["cname"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?><?php endif; ?></dd>
          </dl><?php endif; ?>
          <dl>
            <dt>按地区</dt>
            <dd id="areahtml"><?php echo ($area); ?></dd>
          </dl>
          <dl>
            <dt>按时间</dt>
            <dd id="yearhtml"><?php echo ($year); ?></dd>
          </dl>
          <dl class="nobrd">
            <dt>按拼音</dt>
            <dd class="letter"><?php echo get_letter_url($cid,$letter,'video');?></dd>
          </dl>
        </div>
      </div>
      <span class="bl"></span><span class="br"></span>
    </div>
    <!-- -->
    <div class="blank"></div>
    <div class="mov bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct"><div class="hd">
          <h3><?php echo ($cname); ?></h3>
          <div class="sort"> 排序：<a href="<?php echo ($webpath); ?>index.php?s=video/lists/id/<?php echo ($cid); ?>/order/addtime">按时间</a>| <a href="<?php echo ($webpath); ?>index.php?s=video/lists/id/<?php echo ($cid); ?>/order/weekhits">按人气</a>| <a href="<?php echo ($webpath); ?>index.php?s=video/lists/id/<?php echo ($cid); ?>/order/score">按评分</a> </div>
        </div>
        <ul class="mov_list"><?php $tag['name'] = 'video';$tag['cid'] = ''.$cid.'';$tag['limit'] = '10';$tag['order'] = ''.$order.''; $__LIST__ = get_tag_gxlist($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li>
          <a href="<?php echo ($video["readurl"]); ?>" target="_blank"><img src="<?php echo ($video["picurl"]); ?>" onerror="this.src='<?php echo ($webpath); ?>views/images/nophoto.jpg'" border="0" width="119" height="170" alt="<?php echo ($video["title"]); ?>"/></a>
          <a href="<?php echo ($video["readurl"]); ?>" target="_blank" class="title" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,12)); ?></a>
          <p>导演：<?php echo (($video["director"])?($video["director"]):'未知'); ?> <br />主演：<?php echo (get_actor_url(get_replace_html($video["actor"],0,6))); ?><br />地区：<?php echo ($video["area"]); ?>   年份：<?php echo (($video["year"])?($video["year"]):'未录入'); ?><br />评分：<span class="score"><?php echo ($video["score"]); ?></span> </p>
          <p class="episode_des">剧集介绍：<?php echo (get_replace_html($video["content"],0,36,'utf-8',true)); ?></p>
          <p class="bar"><a href="<?php echo ($video["readurl"]); ?>" class="view">详细资料&gt;&gt;</a><a href="<?php echo ($video["playerurl"]); ?>" class="watch">立即观看</a></p>
        </li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?></ul>
        <?php if(($count)  >  "10"): ?><div class="pages"><?php echo ($pages); ?></div><?php endif; ?>
      </div><span class="bl"></span><span class="br"></span></div>
  </div>
</div>
<div id="footer">
	<?php echo ($copyright); ?><br />
	Copyright © 2007 - 2011 <a href="<?php echo ($weburl); ?>"><?php echo ($webname); ?></a> Some Rights Reserved <?php echo ($icp); ?> <?php echo ($tongji); ?> <a href="<?php echo ($baidusitemap); ?>">sitemap</a> <a href="<?php echo ($googlesitemap); ?>">sitemap</a><br />
</div>
</body>
</html>