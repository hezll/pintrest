<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>友情链接管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/admin_all.js"></script>
</head>
<body>
<?php if(!empty($list)): ?><table width="98%" border="0" cellpadding="4" cellspacing="1" class="table">
<form action="?s=Admin/Link/Update" method="post" id="gxform" name="gxform">
<tr class="table_title">
  <td colspan="8">友情链接列表</td>
</tr>
<tr class="list_head ct">
  <td width="60">编号</td>
  <td colspan="2" >网站名称</td>
  <td width="250">网站地址</td>
  <td width="250">网站LOGO</td>
  <td width="70">顺序</td>
  <td width="70">形式</td>
  <td width="60">删除</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><tr class="tr ct">
<td class="td"><?php echo ($gxcms["id"]); ?><input name="ids[]" type="checkbox" value="<?php echo ($gxcms["id"]); ?>" class="noborder" checked></td>
<td class="td"><?php echo ($gxcms["title"]); ?></td>
<td width="120" class="td ct"><input name="titles[<?php echo ($gxcms["id"]); ?>]" type="text" value="<?php echo ($gxcms["title"]); ?>" maxlength="50" style="width:100px; text-align:center"></td>
<td class="td"><input name="urls[<?php echo ($gxcms["id"]); ?>]" type="text" value="<?php echo ($gxcms["url"]); ?>" maxlength="250" style="width:230px"></td>
<td class="td"><input name="logos[<?php echo ($gxcms["id"]); ?>]" type="text" value="<?php echo ($gxcms["logo"]); ?>" maxlength="250" style="width:230px"></td>
<td class="td"><input name="oids[<?php echo ($gxcms["id"]); ?>]" type="text" value="<?php echo ($gxcms["oid"]); ?>" size="3" maxlength="5" class="ct"></td>
<td class="td"><select name="types[<?php echo ($gxcms["id"]); ?>]"><option value="1">文字</option><option value="2" <?php if(($gxcms["type"])  ==  "2"): ?>selected<?php endif; ?>>图片</option></select></td>
<td class="td"><a href="?s=Admin/Link/Del/id/<?php echo ($gxcms["id"]); ?>" onClick="return confirm('确定删除该友情链接吗?')">删除</a></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
<td colspan="8" class="tr"><input type="button" id="checkall" value="全/反选" class="bginput"> <input type="submit" value="批量修改" name="Submit" class="bginput"  onclick="gxform.action='?s=Admin/Link/Update';"/>&nbsp;&nbsp;<input type="submit" value="批量删除" onClick="if(confirm('确定要删除吗?')){gxform.action='?s=Admin/Link/Delall';}else{return false}" class="bginput"/></td>
</tr>
</form>
</table><?php endif; ?>
<table width="98%" border="0" cellpadding="4" cellspacing="1" class="table">
<form action="?s=Admin/Link/Insert" method="post">
<tr class="table_title">
  <td colspan="2">添加友情链接</td>
</tr>        
<tr class="tr">
  <td width="100" >网站名称:</td>
  <td ><input name="title" type="text" maxlength="50" value="<?php echo ($title); ?>" style="width:200px"> *</td>
</tr>
<tr class="tr">
  <td >网站地址:</td>
  <td ><input name="url" type="text" size="40" value="http://" maxlength="250" style="width:200px"> *</td>
</tr>
 <tr class="tr">
  <td >LOGO地址：</td>
  <td ><input name="logo" type="text" size="40" value="http://" maxlength="250" style="width:200px"></td>
</tr>       
<tr class="tr">
  <td >连接排序：</td>
  <td ><input name="oid" type="text" value="<?php echo ($oid); ?>" maxlength="5" style="width:40px;text-align:center" title="越小越前面"></td>
</tr>
<tr class="tr">
  <td >链接类型：</td>
  <td ><select name="type"><option value="1">文字链接</option><option value="2">图片链接</option></select></td>
</tr>       
<tr class="tr">
  <td colspan="2"><input class="bginput" type="submit" name="submit" value="提交"></td>
</tr>
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