<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title>站点安装-许可协议</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/install.css'>
</head>
<body >
<div id='installbox'>
	<div class='msgtitle'><?php echo C("cms_name");?><?php echo C("cms_var");?> 安装向导</div>
  <table width="573" height="23" border="0" cellpadding="0" cellspacing="0" style="text-align:center; font-weight:bold;font-size:12pt;background:url(views/images/install/set04_top_nav.gif);margin:10px 0 0 10px;">
    <tr>
      <td style="color:#666; text-align:center"><span style="display:block; float:left; width:20%; font-size:12px;">1. 许可协议</span><span style="display:block;float:left;width:25%;font-size:12px;">2. 环境检测</span><span style="display:block;float:left;width:25%;font-size:12px;">3. 数据库设置</span><span style="display:block; float:left; width:25%; font-size:12px; color: #FFF;">4. 安装完成</span></td>
    </tr>
  </table>
  <div id='msgbody'>
<h3 style="text-align:center; line-height:100px;">恭喜你！<?php echo C("cms_name");?><?php echo C("cms_var");?>安装成功！</h3>
<div style="text-align:center; font-size:16px;font-family:'黑体';color:#1B76B7;">现在就开始体验<?php echo C("cms_name");?><?php echo C("cms_var");?>吧！</div>
<div style="margin-top:2em;align:center;width:100%;">
<table width="50%" height="80" align=center>
   <tr>
     <td align=left><input type="button" class="btn" style=" width:120px;height:30px;" value="进入网站首页"  onClick="javascript:location.href='index.php';"/></td>
     <td align=right><input type="button" class="btn" style=" width:120px;height:30px;" value="登陆网站后台"  onClick="javascript:location.href='admin';"/></td>
   </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr class="firstalt" style="height:10px">
        <td colspan="2" align="center"><div id='msgbottom'>Powered By <?php echo C("cms_name");?> <?php echo C("cms_var");?> </div></td>
      </tr>
</table>
</div>
</div>
</body>
</html>