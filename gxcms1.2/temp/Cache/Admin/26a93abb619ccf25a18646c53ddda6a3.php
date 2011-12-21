<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>影视列表</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'>
<link rel='stylesheet' type='text/css' href='./views/css/dialog.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/admin_all.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/dialog.js"></script>
<script type="text/javascript">
<!--
function Imgshow(vname,img){
	art.dialog({
    padding: 0,
    title: vname,
    content: '<img src="'+img+'" width="183" height="248" alt="'+vname+'" />',
    lock: true
});
	}
	-->
</script>
</head>
<body>
<table width="98%" border="0" cellpadding="5" cellspacing="1" class="table">
<tr>
  <td colspan="8" class="table_title"><span class="fl">视频数据管理(<a href="#" onClick="if(confirm('消耗资源较多，请勿在高峰期执行该操作!')){dialogPop('?s=Admin/Video/Downimg','远程图片下载');}else{return false}" style="color:#f00;">下载远程图片</a>)</span><span class="fr"><a href="?s=Admin/Video/Add">添加视频</a></span></td>
</tr>
<form action="?s=Admin/Video/Show/keyword" method="post" id="gxform" name="gxform">
<tr class="tr">
<td colspan="8"><span style="float:left"><label>搜索影片：&nbsp;&nbsp;</label><select id="selectFilter" onChange="self.location.href='?s=Admin/Video/Show/cid/'+this.value+''">
<option value="">所有分类</option><?php if(is_array($list_channel_video)): $i = 0; $__LIST__ = $list_channel_video;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>" <?php if(($gxcms["id"])  ==  $cid): ?>selected<?php endif; ?>><?php echo ($gxcms["cname"]); ?></option><?php if(is_array($gxcms['son'])): $i = 0; $__LIST__ = $gxcms['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>" <?php if(($gxcms["id"])  ==  $cid): ?>selected<?php endif; ?>>├ <?php echo ($gxcms["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?></select></span>&nbsp;&nbsp;<input name="keyword" type="text" id="keyword" size="20" value="<?php echo (htmlspecialchars($keyword)); ?>">&nbsp;&nbsp;<input type="submit" value="搜 索" class="bginput" title="输入关键字搜索影片" onKeyDown="diableReturn(event)"/>&nbsp;&nbsp;</td>
</tr>
<tr align="center" class="list_head">
<td width="70">编号
  <?php if(($order)  ==  "id desc"): ?><a href="?s=Admin/Video/Show/type/id/order/asc"><img src="./views/images/admin/up.gif" border="0" alt="点击按ID升序排列"></a><?php else: ?><a href="?s=Admin/Video/Show/type/id/order/desc"><img src="./views/images/admin/down.gif" border="0" alt="点击按ID降序排列"></a><?php endif; ?></td>
<td >视频名称</td>
<td width="80">视频分类</td>
<td width="50">连载</td>
<td width="70">人气
  <?php if(($order)  ==  "hits desc"): ?><a href="?s=Admin/Video/Show/type/hits/order/asc"><img src="./views/images/admin/up.gif" border="0" alt="点击按人气升序排列"></a><?php else: ?><a href="?s=Admin/Video/Show/type/hits/order/desc"><img src="./views/images/admin/down.gif" border="0" alt="点击按人气降序排列"></a><?php endif; ?></td>
<td width="90">星级
  <?php if(($order)  ==  "stars desc"): ?><a href="?s=Admin/Video/Show/type/stars/order/asc"><img src="./views/images/admin/up.gif" border="0" alt="点击按星级升序排列"></a><?php else: ?><a href="?s=Admin/Video/Show/type/stars/order/desc"><img src="./views/images/admin/down.gif" border="0" alt="点击按星级降序排列"></a><?php endif; ?></td>
<td width="90">时间<?php if(($order)  ==  "addtime desc"): ?><a href="?s=Admin/Video/Show/type/addtime/order/asc"><img src="./views/images/admin/up.gif" border="0" alt="点击按时间升序排列"></a><?php else: ?><a href="?s=Admin/Video/Show/type/addtime/order/desc"><img src="./views/images/admin/down.gif" border="0" alt="点击按时间降序排列"></a><?php endif; ?></td>
<td width="105">操作</td>
</tr>
<?php if(is_array($list_video)): $i = 0; $__LIST__ = $list_video;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><tr class="tr" align="center">
<td align="left"><input name='ids[]' type='checkbox' value='<?php echo ($gxcms["id"]); ?>' class="noborder" checked="checked"><?php echo ($gxcms["id"]); ?></td>
<td align="left" style="color:#999"><?php if(c('url_html') > 0): ?><?php if(($gxcms['status'])  ==  "1"): ?><a href="javascript:showhtml('<?php echo ($gxcms["id"]); ?>');"><font color="green" style="margin-right:5px">生成</font></a><?php else: ?><font  style="margin-right:5px">生成</font></a><?php endif; ?><a id="html_<?php echo ($gxcms["id"]); ?>" style=" position: absolute; margin-top:-2px; height:18px; background:#FFFFFF; list-style:none; overflow:hidden;"></a><?php endif; ?><a href="<?php echo ($gxcms["videourl"]); ?>" target="_blank"><?php echo get_color_title(get_replace_html($gxcms['title'],0,30),$gxcms['color']);?></a> <?php echo (get_replace_html($gxcms["intro"],0,30)); ?><?php if(!empty($gxcms['picurl'])): ?><?php if(get_replace_html($gxcms['picurl'],0,7) == 'fail://'): ?><img src="./views/images/admin/break.jpg"  title="图片下载失败"/><?php else: ?><a href="javascript:void(0)" onclick="Imgshow('<?php echo (get_replace_html($gxcms["title"],0,12)); ?>','<?php echo (get_img_url($gxcms["picurl"])); ?>');"><img src="./views/images/admin/pic.jpg" /></a><?php endif; ?><?php endif; ?><sup id="serial_<?php echo ($gxcms["id"]); ?>" title="连载集数"><?php if(!empty($gxcms['serial'])): ?><?php echo ($gxcms["serial"]); ?><?php endif; ?></sup>
</td>
<td class="td"><a href="<?php echo ($gxcms["channelurl"]); ?>"><?php echo ($gxcms["cname"]); ?></a></td>
<td id="isser<?php echo ($gxcms["id"]); ?>" class="td"><?php if(!empty($gxcms["serial"])): ?><img src="./views/images/admin/icon_01.gif" title="连载中" onClick="ToBContinue('<?php echo ($gxcms["id"]); ?>');" style="cursor:pointer;"><?php else: ?><img src="./views/images/admin/icon_02.gif" title="完结" onClick="ToBContinue('<?php echo ($gxcms["id"]); ?>');" style="cursor:pointer;"><?php endif; ?></td>
<td class="td"><?php echo ($gxcms["hits"]); ?></td>
<td id="stars_<?php echo ($gxcms["id"]); ?>"><?php if(is_array($gxcms['stararr'])): $i = 0; $__LIST__ = $gxcms['stararr'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcmsstars): ++$i;$mod = ($i % 2 )?><span class="star-<?php echo ($gxcmsstars); ?>" onClick="sendStar(parseInt('<?php echo ($gxcms["id"]); ?>'),parseInt('<?php echo ($key); ?>'),this)"; title="推荐为<?php echo ($key); ?>星级"></span><?php endforeach; endif; else: echo "" ;endif; ?></td>
<td class="td"><?php echo (date('Y-m-d',$gxcms["addtime"])); ?></td>
<td class="td"><a href="?s=Admin/Video/Add/id/<?php echo ($gxcms["id"]); ?>" title="点击修改影片">编辑</a> <a href="?s=Admin/Video/Del/id/<?php echo ($gxcms["id"]); ?>" onClick="return confirm('确定删除该视频吗?')" title="点击删除影片">删除</a>  <?php if(($gxcms['status'])  ==  "1"): ?><a href="?s=Admin/Video/Status/id/<?php echo ($gxcms["id"]); ?>/sid/0" title="点击隐藏影片">隐藏</a><?php else: ?><a href="?s=Admin/Video/Status/id/<?php echo ($gxcms["id"]); ?>/sid/1" title="点击显示影片"><font color="red">显示</font></a><?php endif; ?></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr class="tr"><td colspan="8" class="pages"><?php echo ($listpages); ?></td></tr>
<tr class="tr"><td colspan="8"><input type="button" id="checkall" value="全/反选" class="bginput">&nbsp;&nbsp;<input type="submit" value="批量审核" class="bginput" onClick="gxform.action='?s=Admin/Video/Statusall/sid/1';" />&nbsp;&nbsp;<input type="submit" value="取消审核" class="bginput" onClick="gxform.action='?s=Admin/Video/Statusall/sid/0';" />&nbsp;&nbsp;<input type="submit" value="批量删除" onClick="if(confirm('删除后将无法还原,确定要删除吗?')){gxform.action='?s=Admin/Video/Delall';}else{return false}" class="bginput"/>&nbsp;&nbsp;<input type="button" value="批量生成" id="createhtml" name="Videoid" class="bginput" <?php if((C("url_html"))  !=  "1"): ?>disabled<?php endif; ?>/>&nbsp;&nbsp;<input type="button" id="changecid" name="changecid" class="bginput" value="批量移动"/> <span style="display:none" id="changeciddiv"><select name="changecid"><option value="">选择目标分类</option><?php if(is_array($list_channel_video)): $i = 0; $__LIST__ = $list_channel_video;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>" ><?php echo ($gxcms["cname"]); ?></option><?php if(is_array($gxcms['son'])): $i = 0; $__LIST__ = $gxcms['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($gxcms["id"]); ?>" >├ <?php echo ($gxcms["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?></select> <input type="submit" class="bginput" value="确定转移" onClick="gxform.action='?s=Admin/Video/Changecid';"/></span></td>
</tr>
</form>
</table>
<!--连载框 -->
<div id="msg_tbc" class="tbc-block"></div>
<!--浮动层 -->
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery_jqmodal.js"></script>
<link rel='stylesheet' type='text/css' href='./views/css/jquery_jqmodal.css'>
<style>#dia_title{height:25px;line-height:25px}.jqmWindow{height:200px;}</style>
<div class="jqmWindow" id="dialog">
<div id="dia_title"><span class="jqmTitle"></span><a href="javascript:void(0)" class="jqmClose" title="关闭窗口"></a></div>
<div id="dia_content"></div>
</div>
<script language="JavaScript" type="text/javascript">
//弹出浮动层 $('#dialog').jqm({modal: true, trigger: 'a.showDialog'});
$('#dialog').jqm({modal: false, trigger: '#window' , opacity:'0.1'});
</script>
<style>#dia_title{height:25px;line-height:25px}.jqmWindow{height:300px;width:500px;}</style>
<script language="JavaScript" type="text/javascript">
function showhtml(id){
	$.get('?s=Admin/Html/Videoid/ids/'+id,null,function(data){
		$("#html_"+id).html('<font color=#ff0000>'+data+'</font>');
		window.setTimeout(function(){
			$("#html_"+id).html('');
		},1000);
	});
}
//ajax请求:选择星级控制
var sendStar = function(videoid,star,obj){
	$.ajax({
		  type: 'get',
		  url: "?s=Admin/Video/Stars/id/"+videoid+"/sid/"+star,
		  data: videoid,
		  success:function(){
			var collect = obj.parentNode.children;
			for(var i=0;i<collect.length;i++)
			{
				if(star<=i)
				{
					collect[i].className = 'star-0';
				}else{
					collect[i].className = 'star-1';
				}
			}
		}
	});
}
//ajax请求:弹出设置连载信息浮动层;
var ToBContinue = function(id){
	var offset = $("#isser"+id).offset();
	var top = offset.top+55;
	var left = offset.left-260;
	var serial = $("#serial_"+id).html();
	var html = '连载至第 <input id="tbc_serial" value="'+serial+'" type="text" size="5" style="text-align:center" title="完结请填写0" maxlength="25"/> 集 <input type="button" class="bginput" onClick="submitVideoState('+id+')" value="确定" style="cursor:pointer" /> <input type="button" class="bginput" onClick="closeTBC();" value="取消" style="cursor:pointer" />';
	$('#msg_tbc').css({left:left,top:top});
	$('#msg_tbc').html(html);
	$('#msg_tbc').show(1,function(){
		$('#msg_tbc').animate({
			"top": "-=50px",
			"opacity": 1
		});
	});	
}
//信息浮动层:(确认后)>改变连载图标并且在影片名称后面提示连载信息');
var submitVideoState = function(videoid){
	var serial = $('#tbc_serial').val();
	$.ajax({
		type : "post",
		url : "?s=Admin/Video/Serial",
		data: "id="+videoid+"&sid="+serial,
		success:function($string){
			closeTBC();
			if(serial=='0'||serial=='') {
				$('#serial_'+videoid).html('');
				$("#isser"+videoid+'>img').attr('src','./views/images/admin/icon_02.gif');
			}else{
				$('#serial_'+videoid).html(serial);
				$("#isser"+videoid+'>img').attr('src','./views/images/admin/icon_01.gif');				
			}
		}		
	});
}
//信息浮动层:(取消后，浮动层消失);
var closeTBC = function(id){
	$('#msg_tbc').animate({
		"top": "-=50px",
		"opacity": 0
	});
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