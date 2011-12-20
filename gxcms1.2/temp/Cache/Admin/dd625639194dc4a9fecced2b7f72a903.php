<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE>管理中心-<?php echo C("web_name");?></TITLE>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel='stylesheet' type='text/css' href='./views/css/admin_index.css'> 
<link rel='stylesheet' type='text/css' href='./views/css/admin_top.css'>
<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'> 
<link rel='stylesheet' type='text/css' href='./views/css/dialog.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/dialog.js"></script>

<script language="JavaScript1.2">if(self!=top){top.location=self.location;}</script> 
<script>
	function Mapshow(){	
    art.dialog({id:'map',iframe:'?s=Admin/Index/menuMap', title:'后台地图', width:'650', height:'500', lock:true});

   }
	</script>
    <script language="javascript">
$(document).ready(function(){
	$("#delcache").click(function(){
		$ajaxurl = $(this).attr('href');
		$.get($ajaxurl,null,function(data){
			$("#cache").show();
			$("#cache").html(' <font color=#ff0000>'+data+'</font>');
			window.setTimeout(function(){
				$("#cache").hide();
			},2000);
		});
		return false;
	});
	$("#cache").click(function(){
		$("#cache").hide();
		return false;
	});
});

function left(url){
	leftfra.show(url);
}

function ConClass(id){
	var i;
    var str=$('#d'+id).text()+' >';
	for(i=1;i<=9;i++){
		if (id==i) {
		$('#d'+i).addClass('thisclass');
		}else{
		$('#d'+i).removeClass('thisclass');	
		}
	}
	$('#current').html(str);
    //alert($('#d'+i));
}
</script>

</head>
<body  scroll='no'  >


<div class="topnav">
  <div class="sitenav">
    <div class=welcome>您好：<span class="username"><?php echo $_SESSION["user"];?> </span>，欢迎使用<?php echo C("cms_name");?>。 </div>
    <div class=sitelink><a href="javascript:Mapshow();">功能地图</a> |<a href="?s=admin/index" target="_parent">后台首页</a> | <a href="./" target="_blank">网站主页</a> | <a href="<?php echo C("cms_url");?>" target="_blank">官方论坛</a> | <a class="top-txt" href="?s=Admin/Cache/Del" target="frmright" id="delcache">更新缓存</a><a id="cache"></a></div>
  </div>
  <div class="leftnav">
    <ul>
      <li class="navleft"></li>
      <li id="d1" style="margin-left: -1px" class="thisclass"  onClick="ConClass(1);"><a href="?s=Admin/Config/Conf/id/web" target="main" onClick="left('?s=Admin/Index/Left/id/config');">系统设置</a></li>
      <li id="d2" onClick="ConClass(2);"><a href="?s=Admin/Video/Show" target="main" onClick="left('?s=Admin/Index/Left/id/content');">内容管理</a></li>
      <li id="d3" onClick="ConClass(3);"><a href="?s=Admin/Collect/Show" target="main" onClick="left('?s=Admin/Index/Left/id/collect');">采集管理</a></li>
      <li id="d4" onClick="ConClass(4);"><a href="?s=Admin/Html/Show" target="main" onClick="left('?s=Admin/Index/Left/id/html');">静态生成</a></li>
      <li id="d5" onClick="ConClass(5);"><a href="?s=Admin/User/Show" target="main" onClick="left('?s=Admin/Index/Left/id/user');">用户管理</a></li>
      <li id="d6" onClick="ConClass(6);"><a href="?s=Admin/Tpl/Show" target="main" onClick="left('?s=Admin/Index/Left/id/tpl');">模板管理</a></li>
      <li id="d7" onClick="ConClass(7);"><a href="?s=Admin/Data/Showtable" target="main" onClick="left('?s=Admin/Index/Left/id/data');">数据库管理</a></li>
      <li id="d8" onClick="ConClass(8);"><a href="?s=Admin/Cache/Show" target="main" onClick="left('?s=Admin/Index/Left/id/tool');">扩展工具</a></li>
      <li id="d9" style="margin-right: -1px"><a href="?s=admin/login/logout" target="_parent">注销登录</a></li>
      <li class="navright"></li>
    </ul>
  </div>
</div>



<div id="Maincontent" style="margin: auto;">
  <div id="leftMenu">
  <iframe src="?s=Admin/Index/left" id="leftfra" name="leftfra" frameborder="0" scrolling="no"  style="border:none" width="100%" height="100%" ></iframe>
  </div>
  
  
  <div id="mainNav">
  <div class="cur_position"><div class="cur">当前位置：<span id='current'></span></div></div>

  <div class="mframe" style="position:relative; overflow:hidden">
 <iframe name="main" id="main" src="?s=Admin/Index/main" frameborder="false" scrolling="auto" style="border:none; margin-bottom:10px;"  width="100%" height="auto" ></iframe>
  </div>

 </div>
</div>
<script type="text/javascript"> 
//clientHeight-0; 空白值 iframe自适应高度
function windowW(){
	if($(window).width()<980){
			$('#Maincontent').css('width',980+'px');
            $('.topnav').css('width',980+'px');
			$('body').attr('scroll','');
			$('body').css('overflow','');
	}
}
windowW();
$(window).resize(function(){
	if($(window).width()<980){
		windowW();
	}else{
        $('.topnav').css('width','');
		$('#Maincontent').css('width',100+'%');
		$('body').attr('scroll','no');
		$('body').css('overflow','hidden');

	}
});
window.onresize = function(){
	var heights = document.documentElement.clientHeight-150;document.getElementById('main').height = heights;
	var openClose = $("#main").height()+39;
}
window.onresize();
</script>

</body>

</html>