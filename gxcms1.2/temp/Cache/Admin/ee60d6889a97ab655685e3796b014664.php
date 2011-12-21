<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>广告管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/admin_all.js"></script>
</head>
<body>
<?php if(!empty($list)): ?><table width="98%" border="0" cellpadding="4" cellspacing="1" class="table">
<form action="?s=Admin/Adsense/Update" method="post" id="gxform" name="gxform">
<tr class="table_title"><td colspan="5">网站广告列表</td></tr> 
<tr class="ct list_head">
  <td width="50">编号</td>
  <td width="120">广告标识</td>
  <td width="200">模板调用代码</td>
  <td >广告联盟代码</td>
  <td width="90">操作</td>
</tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><tr class="tr ct">
  <td height="50"><?php echo ($gxcms["id"]); ?><input name="ids[]" type="checkbox" value="<?php echo ($gxcms["id"]); ?>" checked class="noborder"></td>
  <td class="td ct"><input name="titles[]" type="text" value="<?php echo ($gxcms["title"]); ?>" maxlength="50" style="width:90px"></td>  
  <td class="td ct"><input name="tpl[]" type="text" value="{:get_cms_ads('<?php echo ($gxcms["title"]); ?>')}" maxlength="50" style="width:180px" disabled="disabled"></td>
  <td class="td"><textarea name="contents[]" rows="3" style="width:98%"><?php echo (htmlspecialchars($gxcms["content"])); ?></textarea></td>   
  <td class="td"><a href="?s=Admin/Adsense/Add/id/<?php echo ($gxcms["id"]); ?>" target="_blank">预览</a> <a href="?s=Admin/Adsense/Del/id/<?php echo ($gxcms["id"]); ?>" onClick="if(confirm('确定要删除吗?')){gxform.action='?s=Admin/Adsense/Delall';}else{return false}">删除</a></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr><td colspan="5" class="tr"><input type="button" id="checkall" value="全/反选" class="bginput"> <input type="submit" value="批量修改" name="Submit"  class="bginput"/> <input type="submit" value="批量删除" onClick="if(confirm('确定要删除吗?')){gxform.action='?s=Admin/Adsense/Delall';}else{return false}" class="bginput"/></td></tr> 
</form>
</table><?php endif; ?>
<a name="add"></a>
<table width="98%" border="0" cellpadding="4" cellspacing="1" class="table">
<form action="?s=Admin/Adsense/Insert" method="post">
<tr class="table_title"><td colspan="4">添加广告</td></tr>  
<tr class="tr">
<td width="100" class="rt">广告标识：</td>
<td colspan="3"><input name="title" type="text" maxlength="50"> * 注:禁止使用中文</td>
</tr>
<tr class="tr">
<td class="rt">广告代码：</td>
<td colspan="3"><textarea name="content" cols="80" style="height:100px; width:400px"></textarea></td>
</tr>       
<tr class="tr">
<td colspan="4"><input class="bginput" type="submit" name="submit" value="提交"> <input class="bginput" type="reset" name="Input" value="重置" ></td>
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