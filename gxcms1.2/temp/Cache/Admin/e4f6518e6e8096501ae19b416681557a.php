<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>采集节点项目管理-<?php echo C("web_name");?></title>
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'>
<link rel='stylesheet' type='text/css' href='./views/css/collect.css'>
<link rel='stylesheet' type='text/css' href='./views/css/dialog.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/admin_all.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/collect.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/dialog.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/formvalidator.js"></script>
<script type="text/javascript">
<!--
function ColOne(nid)
{ 
	location = "?s=Admin/CustomCollect/ColRun/action/real/type/all/stime/"+encodeURI(document.getElementById('stime'+nid).value)+"/nid/"+nid;
}

-->
</script>
<style type="text/css">
<!--
.tr strong a{color:#f30;}
-->
</style>
</head>
<body>
<table width="98%" border="0" cellpadding="5" cellspacing="1" class="table">
<tr>
  <td colspan="6" class="table_title">
  <ul>
  <li><a  href="?s=Admin/CustomCollect/ListShow">采集节点管理</a> </li>
  <li><a href="?s=Admin/CustomCollect/Add">添加采集节点</a></li>
    <li><a href="?s=Admin/CustomCollect/Manage/action/import">导入采集规则</a></li>
  </ul>
  </td>
</tr>
<?php if(!empty($cache)): ?><tr class="tr"><td colspan="6"><strong><a href="<?php echo ($cache); ?>">上次有采集任务没有完成，是否接着采集?</a></strong></td></tr><?php endif; ?>
<form action="" method="post" id="gxform" name="gxform">
<tr align="center" class="list_head">
<td width="2%"></td>
<td width='5%'>ID</td>
<td width='15%'>节点项目名称</td>
<td width='30%'>采集目标地址</td>
<td width='15%'>最后采集时间</td>
<td width=''>操作</td>
</tr>
<?php if(is_array($ArrList)): $i = 0; $__LIST__ = $ArrList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr class="tr" align="center">
<td  width="2%"><input name='ids[]' type='checkbox' value='<?php echo ($vo["id"]); ?>' class="noborder"></td>
<td  width='5%'><?php echo ($vo["id"]); ?></td>
<td align="left"><?php echo ($vo["name"]); ?></td>
<td class="td"><?php echo ($vo["urlpage"]); ?></td>
<td class="td"><?php if($vo['lastdate'] == 0): ?>从未采集过<?php else: ?><?php echo (date('Y-m-d H:i:s',$vo["lastdate"])); ?><?php endif; ?></td>
<td class="td" id='action'><span style="color:#333;">间隔 <input type="text" id="stime<?php echo ($vo["id"]); ?>" name="stime" value="3"  style="width:10px;height:16px;line-height:16px;"/> s</span><a href="javascript:ColOne(<?php echo ($vo["id"]); ?>);">采集</a>|<a href="?s=Admin/CustomCollect/ColRun/action/demo/type/all/nid/<?php echo ($vo["id"]); ?>">预览</a>|<a href="?s=Admin/CustomCollect/Add/nid/<?php echo ($vo["id"]); ?>">编辑</a><!--|<a  href="javascript:void(0)"  onclick="copy_spider(<?php echo ($vo["id"]); ?>)">复制</a>-->|<a  href="?s=Admin/CustomCollect/Manage/action/copy/nid/<?php echo ($vo["id"]); ?>">复制</a>|<a href="?s=Admin/CustomCollect/Manage/action/export/nid/<?php echo ($vo["id"]); ?>">导出</a>|<a href="?s=Admin/CustomCollect/Manage/action/del/nid/<?php echo ($vo["id"]); ?>"  onClick="return confirm('确定删除该视频吗?')" title="点击删除影片">删除</a></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>

<tr class="tr"><td colspan="8" class="pages"><?php echo ($pagelist['listpages']); ?></td></tr>
<tr class="tr"><td colspan="8"><span><input type="button" id="checkall" value="全/反选" class="bginput"></span><span><input type="button" value="导入采集规则" class="bginput" onClick="gxform.action='?s=Admin/Video/Statusall/sid/0';" /></span><span><input type="button" value="采集选中节点" class="bginput" onClick="gxform.action='?s=Admin/Video/Statusall/sid/0';" /></span><span><input type="submit" value="批量删除" onClick="if(confirm('删除后将无法还原,确定要删除吗?')){gxform.action='?s=Admin/CustomCollect/Manage/action/delall';}else{return false}" class="bginput"/></span></td>
</tr>
</form>
</table>
<!--连载框 -->
<div id="msg_tbc" class="tbc-block"></div>
<!--浮动层 -->

<style>#dia_title{height:25px;line-height:25px}.jqmWindow{height:300px;width:500px;}</style>
<script language="JavaScript" type="text/javascript">
function copy_spider(id) {
	art.dialog({id:'test'}).close();
	art.dialog({lock: true,title:'复制采集节点',id:'test',content:'<input type="text" name="data[name]" >',iframe:'?s=Admin/CustomCollect/Manage/action/copy/nid/'+id,width:'350',height:'125'}, function(){var d = art.dialog({id:'test'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){art.dialog({id:'test'}).close()});
}

</script>
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