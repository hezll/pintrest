<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>数据库管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/admin_all.js"></script>
</head>
<body>
<table width="98%" border="0" cellpadding="4" cellspacing="1" class="table">
  <form action="?s=Admin/Data/Insertsql" method="post" id="gxform" name="gxform">
    <tr class="table_title">
      <td>数据库分卷备份 (请选择需要备份的数据库表)</td>
    </tr>
    <?php if(is_array($list_table)): $i = 0; $__LIST__ = $list_table;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><tr class="tr">
        <td ><input type="checkbox" name="ids[]" value="<?php echo ($gxcms); ?>" class="noborder" checked><?php echo ($gxcms); ?></td>
      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
	<tr class="tr">
      <td>每个分卷文件大小：<input type="text" name="filesize" value="2048" size="5" class="ct"> Kb</td>
    </tr>    
    <tr class="tr">
      <td><input type="button" class="bginput" id="checkall" value="全/反选"> <input type="submit" name="submit" value="开始备份" class="bginput"></td>
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