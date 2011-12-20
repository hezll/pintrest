<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo ($keywords); ?>">
<meta name="description" content="<?php echo ($description); ?>">
<title><?php echo ($webtitle); ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<script language="javascript">var SitePath='<?php echo ($webpath); ?>';var SiteMid='<?php echo ($mid); ?>';var SiteCid='<?php echo ($cid); ?>';var SiteId='<?php echo ($id); ?>';</script>
<script language="JavaScript" type="text/javascript" src="<?php echo ($webpath); ?>views/js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo ($webpath); ?>views/js/system.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo ($webpath); ?>views/js/history.js"></script>
<link rel='stylesheet' type='text/css' href='<?php echo ($webpath); ?>views/css/system.css'>
<link rel='stylesheet' type='text/css' href='<?php echo ($tplpath); ?>template.css'>
<link rel="shortcut icon" href="<?php echo ($webpath); ?>favicon.ico" />
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
  <div class="left_col">
	<div class="topbrd"></div>
    <div class="bd"><div class="ct">
        <div class="hd"><h3>最近更新</h3></div>
        <ul class="top index_top_video">
        <?php $tag['name'] = 'video';$tag['limit'] = '12';$tag['order'] = 'addtime desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><?php if(($i)  <  "4"): ?><li><?php else: ?><?php if(($i)  ==  "12"): ?><li class="nobrd b"><?php else: ?><li class="b"><?php endif; ?><?php endif; ?><em><?php echo ($i); ?></em><a href="<?php echo ($video["readurl"]); ?>" target="_blank" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,11)); ?></a> <span><?php echo (get_color_date('m-d',$video["addtime"])); ?></span></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
        </ul>
        <div class="total">今日更新：<span>[<?php echo get_channel_count(0);?>]</span> 总电影数量：<span>[<?php echo get_channel_count(-1);?>]</span></div>
    </div></div>
    <div class="btmbrd"></div>
  </div>
  <div class="right_col">
    <div class="bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="hd"><h3 class="rec"><span>近期热片推荐</span></h3><a href="<?php echo ($topurl); ?>" class="more">热片排行榜&gt;&gt;</a> </div>
        <ul class="mov_pic_list index_rec">
        <?php $tag['name'] = 'video';$tag['limit'] = '10';$tag['order'] = 'stars desc,addtime desc,weekhits desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li><div class="pic"><a href="<?php echo ($video["readurl"]); ?>" target="_blank"><img src="<?php echo ($video["picurl"]); ?>" onerror="this.src='<?php echo ($webpath); ?>views/images/nophoto.jpg'" border="0" height="140" width="98"></a></div><div class="ver"><?php echo (($video["intro"])?($video["intro"]):'最新热片'); ?></div><div class="mid_title"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,8)); ?></a></div></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
        </ul>
      </div>
    <span class="bl"></span><span class="br"></span></div>
  </div>
</div>
<div class="box"><?php echo get_cms_ads('index-96090');?></div>
<div class="box">
  <div class="left_col">
    <div class="topbrd"></div>
    <div class="bd"><div class="ct">
        <div class="hd"><h3><?php echo get_channel_name(2);?>热播榜</h3><a href="<?php echo ($topurl); ?>" class="more">更多&gt;&gt;</a> </div>
        <ul class="top index_top_video">
        <?php $tag['name'] = 'video';$tag['cid'] = '2';$tag['limit'] = '13';$tag['order'] = 'score desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><?php if(($i)  <  "4"): ?><li><?php else: ?><?php if(($i)  ==  "13"): ?><li class="nobrd b"><?php else: ?><li class="b"><?php endif; ?><?php endif; ?><em><?php echo ($i); ?></em><a href="<?php echo ($video["readurl"]); ?>" target="_blank" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,11)); ?></a><?php if(($i)  <  "4"): ?><span style="color:#e02e2e;"><?php else: ?><span><?php endif; ?><?php echo ($video["score"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
        </ul>
    </div></div>
    <div class="btmbrd"></div>
  </div>
  <div class="right_col">
    <div class="bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="hd">
          <h3><?php echo get_channel_name(2);?></h3><a href="<?php echo get_channel_name(2,'showurl');?>" class="more">全部&gt;&gt;</a>
          <div class="sort"><?php $tag['name'] = 'menu';$tag['ids'] = '2'; $__LIST__ = get_tag_gxmenu($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): ++$i;$mod = ($i % 2 );?><?php if(is_array($menu["son"])): $i = 0; $__LIST__ = $menu["son"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menuson): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($menuson["showurl"]); ?>" title="<?php echo ($menuson["cname"]); ?>"><?php echo ($menuson["cname"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?></div>
        </div>
        <div class="index_tv">
          <ul class="mov_pic_list">
          <?php $tag['name'] = 'video';$tag['cid'] = '2';$tag['limit'] = '5';$tag['order'] = 'addtime desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li><div class="pic"><a href="<?php echo ($video["readurl"]); ?>" target="_blank"><img src="<?php echo ($video["picurl"]); ?>" onerror="this.src='<?php echo ($webpath); ?>views/images/nophoto.jpg'" border="0" height="140" width="98"></a></div><div class="ver"><?php echo (($video["intro"])?($video["intro"]):'最新热片'); ?></div><div class="mid_title"><a href="<?php echo ($video["readurl"]); ?>" mid_title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,8)); ?></a></div></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul>
          <div class="mov_text_list"><ul>
          	<?php $tag['name'] = 'video';$tag['cid'] = '2';$tag['limit'] = '5,24';$tag['order'] = 'addtime desc';$tag['mod'] = '6'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 6 );?><?php if(($mod)  ==  "0"): ?><li class="nobrd"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li></ul><ul><?php else: ?><li><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul></div>
        </div>
      </div>
      <span class="bl"></span><span class="br"></span> </div>
  </div>
</div>
<!-- -->
<div class="box">
  <div class="left_col">
    <div class="topbrd"></div>
    <div class="bd"><div class="ct">
        <div class="hd"><h3><?php echo get_channel_name(1);?>热播榜</h3><a href="<?php echo ($topurl); ?>" class="more">更多&gt;&gt;</a> </div>
        <ul class="top index_top_video">
        <?php $tag['name'] = 'video';$tag['cid'] = '1';$tag['limit'] = '10';$tag['order'] = 'score desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><?php if(($i)  <  "4"): ?><li><?php else: ?><?php if(($i)  ==  "10"): ?><li class="nobrd b"><?php else: ?><li class="b"><?php endif; ?><?php endif; ?><em><?php echo ($i); ?></em><a href="<?php echo ($video["readurl"]); ?>" target="_blank" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,11)); ?></a><?php if(($i)  <  "4"): ?><span style="color:#e02e2e;"><?php else: ?><span><?php endif; ?><?php echo ($video["score"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
        </ul>
    </div></div>
    <div class="btmbrd"></div>
  </div>
  <div class="right_col">
    <div class="bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="hd">
          <h3><?php echo get_channel_name(1);?></h3><a href="<?php echo ($topurl); ?>" class="more">全部&gt;&gt;</a>
          <div class="sort"><?php $tag['name'] = 'menu';$tag['ids'] = '1'; $__LIST__ = get_tag_gxmenu($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): ++$i;$mod = ($i % 2 );?><?php if(is_array($menu["son"])): $i = 0; $__LIST__ = $menu["son"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menuson): ++$i;$mod = ($i % 2 )?><a href="<?php echo ($menuson["showurl"]); ?>" title="<?php echo ($menuson["cname"]); ?>"><?php echo ($menuson["cname"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?></div>
        </div>
        <div class="index_mov">
          <ul class="mov_pic_list">
          <?php $tag['name'] = 'video';$tag['cid'] = '1';$tag['limit'] = '5';$tag['order'] = 'addtime desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li><div class="pic"><a href="<?php echo ($video["readurl"]); ?>" target="_blank"><img src="<?php echo ($video["picurl"]); ?>" onerror="this.src='<?php echo ($webpath); ?>views/images/nophoto.jpg'" border="0" height="140" width="98"></a></div><div class="ver"><?php echo (($video["intro"])?($video["intro"]):'最新热片'); ?></div><div class="mid_title"><a href="<?php echo ($video["readurl"]); ?>" mid_title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,8)); ?></a></div></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul>
          <div class="mov_text_list"><ul>
          	<?php $tag['name'] = 'video';$tag['cid'] = '1';$tag['limit'] = '5,12';$tag['order'] = 'addtime desc';$tag['mod'] = '3'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 3 );?><?php if(($mod)  ==  "0"): ?><li class="nobrd"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li></ul><ul><?php else: ?><li><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul></div>
        </div>
      </div>
      <span class="bl"></span><span class="br"></span> </div>
  </div>
</div>
<!-- -->
<div class="box"><?php echo get_cms_ads('index-96090-2');?></div>
<div class="box">
  <div class="left_col">
    <div class="topbrd"></div>
    <div class="bd"><div class="ct">
        <div class="hd"><h3><?php echo get_channel_name(3);?>热播榜</h3><a href="<?php echo ($topurl); ?>" class="more">更多&gt;&gt;</a> </div>
        <ul class="top index_top_video">
        <?php $tag['name'] = 'video';$tag['cid'] = '3';$tag['limit'] = '10';$tag['order'] = 'score desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><?php if(($i)  <  "4"): ?><li><?php else: ?><?php if(($i)  ==  "10"): ?><li class="nobrd b"><?php else: ?><li class="b"><?php endif; ?><?php endif; ?><em><?php echo ($i); ?></em><a href="<?php echo ($video["readurl"]); ?>" target="_blank" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,11)); ?></a><?php if(($i)  <  "4"): ?><span style="color:#e02e2e;"><?php else: ?><span><?php endif; ?><?php echo ($video["score"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
        </ul>
    </div></div>
    <div class="btmbrd"></div>
  </div>
  <div class="right_col">
    <div class="bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="hd">
          <h3><?php echo get_channel_name(3);?></h3><a href="<?php echo ($topurl); ?>" class="more">全部&gt;&gt;</a>
        </div>
        <div class="index_mov">
          <ul class="mov_pic_list">
          <?php $tag['name'] = 'video';$tag['cid'] = '3';$tag['limit'] = '5';$tag['order'] = 'addtime desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li><div class="pic"><a href="<?php echo ($video["readurl"]); ?>" target="_blank"><img src="<?php echo ($video["picurl"]); ?>" onerror="this.src='<?php echo ($webpath); ?>views/images/nophoto.jpg'" border="0" height="140" width="98"></a></div><div class="ver"><?php echo (($video["intro"])?($video["intro"]):'最新热片'); ?></div><div class="mid_title"><a href="<?php echo ($video["readurl"]); ?>" mid_title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,8)); ?></a></div></li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul>
          <div class="mov_text_list"><ul>
          	<?php $tag['name'] = 'video';$tag['cid'] = '3';$tag['limit'] = '5,12';$tag['order'] = 'addtime desc';$tag['mod'] = '3'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 3 );?><?php if(($mod)  ==  "0"): ?><li class="nobrd"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li></ul><ul><?php else: ?><li><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul></div>
        </div>
      </div>
      <span class="bl"></span><span class="br"></span> </div>
  </div>
</div>
<!--内容 网友最爱&动漫&记录片&音乐&体育开始-->
<div class="box">
  <div class="left_col">
    <div class="topbrd"></div>
    <div class="bd"><div class="ct">
        <div class="hd"><h3><?php echo get_channel_name(4);?>热播榜</h3></div>
        <ul class="top">
          <?php $tag['name'] = 'video';$tag['cid'] = '4';$tag['limit'] = '2';$tag['order'] = 'score desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 2 );?><li class="v" style="border:none; margin-top:15px"><a href="<?php echo ($video["readurl"]); ?>" target="_blank" class="pic"><img src="<?php echo ($video["picurl"]); ?>" onerror="this.src='<?php echo ($webpath); ?>views/images/nophoto.jpg'" border="0" height="140" width="98"></a>
          <div class="info">
          <p class="title"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>"><?php echo (get_replace_html($video["title"],0,7)); ?></a></p>
          <p class="score">网友评分：<strong><?php echo ($video["score"]); ?></strong></p>
          <p>说明：<?php echo (get_replace_html($video["content"],0,40)); ?></p>
          </div>
          </li><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
        </ul>
      </div></div>
    <div class="btmbrd"></div>
  </div>
  <div class="right_col">
    <div class="bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="hd"><h3><?php echo get_channel_name(4);?></h3><a href="<?php echo ($topurl); ?>" class="more">全部&gt;&gt;</a> </div>
           <div class="mov_text_list  index_other"><ul>
          	<?php $tag['name'] = 'video';$tag['cid'] = '4';$tag['limit'] = '12';$tag['order'] = 'addtime desc';$tag['mod'] = '3'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 3 );?><?php if(($mod)  ==  "0"): ?><li class="nobrd"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li></ul><ul><?php else: ?><li><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul></div>
      </div>
      <span class="bl"></span><span class="br"></span></div>
    <div class="blank"></div>
    <div class="bd"><span class="tl"></span><span class="tr"></span>
        <div class="ct">
        <div class="hd"><h3><?php echo get_channel_name(5);?></h3><a href="<?php echo ($topurl); ?>" class="more">全部&gt;&gt;</a> </div>
           <div class="mov_text_list  index_other"><ul>
          	<?php $tag['name'] = 'video';$tag['cid'] = '5';$tag['limit'] = '12';$tag['order'] = 'addtime desc';$tag['mod'] = '3'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 3 );?><?php if(($mod)  ==  "0"): ?><li class="nobrd"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li></ul><ul><?php else: ?><li><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul></div>
      </div>
      <span class="bl"></span><span class="br"></span> </div>
    <div class="blank"></div>
    <div class="bd"><span class="tl"></span><span class="tr"></span>
      <div class="ct">
        <div class="hd"><h3><?php echo get_channel_name(6);?></h3><a href="<?php echo ($topurl); ?>" class="more">全部&gt;&gt;</a> </div>
           <div class="mov_text_list  index_other"><ul>
          	<?php $tag['name'] = 'video';$tag['cid'] = '6';$tag['limit'] = '12';$tag['order'] = 'addtime desc';$tag['mod'] = '3'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$video): ++$i;$mod = ($i % 3 );?><?php if(($mod)  ==  "0"): ?><li class="nobrd"><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li></ul><ul><?php else: ?><li><a href="<?php echo ($video["readurl"]); ?>" title="<?php echo ($video["title"]); ?>" target="_blank"><?php echo (get_replace_html($video["title"],0,10)); ?></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?>
          </ul></div>
      </div>
      <span class="bl"></span><span class="br"></span> </div>
  </div>
</div>
<div class="friend_link bd">
<span class="tl"></span><span class="tr"></span>
  <div class="ct">
    <div class="hd"><h3>友情链接</h3></div>
    <ul><li><a href="http://www.gxcms.com" target="_blank">光线CMS</a></li>
    <?php $tag['name'] = 'link';$tag['limit'] = '100';$tag['order'] = 'type asc,oid desc'; $__LIST__ = get_tag_gxcms($tag); if(is_array($__LIST__)): $i = 0;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$link): ++$i;$mod = ($i % 2 );?><?php if(($link["type"])  ==  "1"): ?><li><a href="<?php echo ($link["url"]); ?>" target="_blank"><?php echo (get_replace_html($link["title"],0,8)); ?></a></li>
    <?php else: ?>
    <li><a href="<?php echo ($link["url"]); ?>" target="_blank"><img src="<?php echo ($link["logo"]); ?>" alt="<?php echo ($link["title"]); ?>" width="88" height="31"/></a></li><?php endif; ?><?php endforeach; endif; else: echo "" ;endif;unset($__LIST__);unset($tag);?></ul>
  </div>
  <span class="bl"></span><span class="br"></span> 
</div>
<div id="footer">
	<?php echo ($copyright); ?><br />
	Copyright © 2007 - 2011 <a href="<?php echo ($weburl); ?>"><?php echo ($webname); ?></a> Some Rights Reserved <?php echo ($icp); ?> <?php echo ($tongji); ?> <a href="<?php echo ($baidusitemap); ?>">sitemap</a> <a href="<?php echo ($googlesitemap); ?>">sitemap</a><br />
</div>
</div>
</body>
</html>