<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>幻灯管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<style>a{ color: #666666}</style>
</head>
<body>
<table width="98%" border="0" cellpadding="4" cellspacing="1" class="table">
<tr class="table_title">
  <td colspan="3">首页幻灯片列表</td>
</tr>
<?php if(is_array($list_slide)): $i = 0; $__LIST__ = $list_slide;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><tr class="tr ct">
<td width="180"  class="ct" style="padding:7px 0px 2px 0px;"><a href="<?php echo ($gxcms["picurl"]); ?>" target="_blank"><img src="<?php echo ($gxcms["picurl"]); ?>" width="160px" height="85px" alt="查看原图" style="border:1px solid #CCCCCC;padding:1px; margin-bottom:3px"></a></td>
<td style="text-align:left; line-height:20px; white-space:normal;"><font style="font-size:14px;color:#333333;font-weight:bold"><?php echo ($gxcms["title"]); ?></font><br><a href="<?php echo ($gxcms["link"]); ?>" target="_blank"><?php echo ($gxcms["content"]); ?></a></td>
<td width="90" align="center"><a href="?s=Admin/Slide/Add/id/<?php echo ($gxcms["id"]); ?>">修改</a> <a href="?s=Admin/Slide/Del/id/<?php echo ($gxcms["id"]); ?>" onClick="return confirm('确定删除该幻灯吗?')" title="点击删除幻灯">删除</a>  <?php if(($gxcms['status'])  ==  "1"): ?><a href="?s=Admin/Slide/Status/id/<?php echo ($gxcms["id"]); ?>/sid/0" title="点击隐藏幻灯">隐藏</a><?php else: ?><a href="?s=Admin/Slide/Status/id/<?php echo ($gxcms["id"]); ?>/sid/1" title="点击显示幻灯"><font color="red">显示</font></a><?php endif; ?></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
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