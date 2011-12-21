<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>静态网页生成</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
</head>
<body>
<table width="98%" border="0" cellpadding="4" cellspacing="1" class="table">
<form action="?s=Admin/Html/Maps" method="post" id="gxform" name="gxform">
<tr class="table_title"><td colspan="13">静态网页生成选项</td></tr>
<tr class="tr"><td colspan="2" ><input type="button" value="生成网站首页" class="bginput" onClick="window.location='?s=Admin/Html/Cindex/';"/ <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?> /> <input type="button" class="bginput" value="一键生成全站" onClick="window.location='?s=Admin/Html/Videoread/go/1';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/> <input type="button" class="bginput" value="一键生成专题" onClick="window.location='?s=Admin/Html/Specialread/go/1';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/> <input type="button" class="bginput" value="一键生成地图" onClick="window.location='?s=Admin/Html/Maps';"/> </td></tr>
<tr class="tr"><td colspan="2" >生成影视列表：<select name="msid"><option value="0">全部分类</option><?php if(is_array($list_channel_video)): $i = 0; $__LIST__ = $list_channel_video;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>"><?php echo ($gxcms["cname"]); ?></option><?php if(is_array($gxcms['son'])): $i = 0; $__LIST__ = $gxcms['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>">├ <?php echo ($gxcms["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?></select> <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Videoshow';" <?php if(($url_html_channel)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
<tr class="tr"><td width="38%" >生成影视内容：<select name="mrid"><option value="0">全部影片</option><?php if(is_array($list_channel_video)): $i = 0; $__LIST__ = $list_channel_video;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>"><?php echo ($gxcms["cname"]); ?></option><?php if(is_array($gxcms['son'])): $i = 0; $__LIST__ = $gxcms['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>">├ <?php echo ($gxcms["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?></select> <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Videoread';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td>
  <td width="62%" >生成影视按时间：<select name="mday"><option value="1">当天</option><option value="2">1天内</option><option value="3">2天内</option><option value="4">3天内</option><option value="5">4天内</option><option value="6">5天内</option><option value="8">7天内</option><option value="31">30天内</option></select> <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Videoday';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td>
</tr> 
<tr class="tr"><td colspan="2" >生成新闻分类：<select name="asid"><option value="0">全部分类</option><?php if(is_array($list_channel_info)): $i = 0; $__LIST__ = $list_channel_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>"><?php echo ($gxcms["cname"]); ?></option><?php if(is_array($gxcms['son'])): $i = 0; $__LIST__ = $gxcms['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>">├ <?php echo ($gxcms["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?></select> <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Infoshow';" <?php if(($url_html_channel)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
<tr class="tr"><td >生成新闻内容：<select name="arid"><option value="0">全部新闻</option><?php if(is_array($list_channel_info)): $i = 0; $__LIST__ = $list_channel_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>"><?php echo ($gxcms["cname"]); ?></option><?php if(is_array($gxcms['son'])): $i = 0; $__LIST__ = $gxcms['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>">├ <?php echo ($gxcms["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?></select> <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Inforead';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td>
  <td >生成新闻按时间：<select name="aday"><option value="1">当天</option><option value="2">1天内</option><option value="3">2天内</option><option value="4">3天内</option><option value="5">4天内</option><option value="6">5天内</option><option value="8">7天内</option><option value="31">30天内</option></select> <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Infoday';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td>
</tr>
<tr class="tr"><td colspan="2" >生成专题列表：<input type="button" value="开始生成"  class="bginput" onClick="window.location='?s=Admin/Html/Specialshow';" <?php if(($url_html_channel)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
<tr class="tr"><td colspan="2" >生成专题内容：<input type="button" value="开始生成" class="bginput" onClick="window.location='?s=Admin/Html/Specialread';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
<tr class="tr"><td colspan="2" >百度SiteMap： 总输出<input id="baidu" name="baiduall" type="text" size="5" maxlength="5" value="500" style="text-align:center">条 每页输出<input id="baidu" name="baidu" type="text" size="5" maxlength="5" value="100" style="text-align:center">条 <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Mapbaidu';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
<tr class="tr"><td colspan="2" >谷歌SiteMap： 总输出<input id="google" name="googleall" type="text" size="5" value="500" maxlength="5" style="text-align:center">条 每页输出<input id="google" name="google" type="text" size="5" maxlength="5" value="100" style="text-align:center">条 <input type="submit" value="开始生成" class="bginput" onClick="gxform.action='?s=Admin/Html/Mapgoogle';" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
<tr class="tr"><td colspan="2" >RSS订阅文件：总输出<input id="rss" name="rss" type="text" size="5" maxlength="5" value="50" style="text-align:center">条 <input name="开始生成" type="submit" class="bginput" onClick="gxform.action='?s=Admin/Html/Maprss';" value="开始生成" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
<tr class="tr"><td colspan="2" >TOP排行榜单：<input id="top" name="top" type="submit" class="bginput" onClick="gxform.action='?s=Admin/Html/Maptop';" value="开始生成" <?php if(($url_html)  !=  "1"): ?>disabled<?php endif; ?>/></td></tr>
</form>
</table>
<style>
#footer, #footer a:link, #footer a:visited {
	clear:both;
	color:#0072e3;
	font:12px/1.5 Arial;
	margin-top:10px;
	text-align:center;
	white-space:nowrap;
}
</style>
<div id="footer">程序版本：<?php echo C("cms_var");?>&nbsp;&nbsp;&nbsp;&nbsp;Copyright © 2010-2011 All rights reserved</div>
</body>
</html>