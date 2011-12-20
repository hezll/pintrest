<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title>站点安装-许可协议</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/install.css'>
<script type="text/javascript">
<!--
function checkebut(){
	    var setup=document.getElementById('setup');
	    if(setup.checked==true){
		document.getElementById('install').disabled='';
		}else{
		document.getElementById('install').disabled='disabled';
		}
	}
-->
</script>
</head>
<body  onLoad="checkebut();">
<form action="<?php echo C("cms_admin");?>?s=Admin/Install/Second" method="post" id="gxform">
<div id='installbox'>
<div class='msgtitle'><?php echo C("cms_name");?><?php echo C("cms_var");?> 安装向导</div>
<table width="573" height="23" border="0" cellpadding="0" cellspacing="0" style="text-align:center; font-weight:bold;font-size:12pt;background:url(views/images/install/set01_top_nav.gif); margin:10px 0 0 10px;">
  <tr>
    <td style="color:#666; text-align:center">
        <span style="display:block;float:left;width:20%;font-size:12px;color:#FFF;">1. 许可协议</span>
        <span style="display:block;float:left;width:25%;font-size:12px;">2. 环境检测</span>
        <span style="display:block;float:left;width:25%;font-size:12px;">3. 数据库设置</span>
        <span style="display:block;float:left;width:25%;font-size:12px;">4. 安装完成</span>        </td>
  </tr>
</table>
<div id='msgbody'>
  <h3><?php echo C("cms_name");?>使用许可协议</h3>
  <div style="text-align:center;">
    <textarea name="textarea" rows="15" style="width:570px;border:1px solid #ccc;font-size:12px;">本软件产品为免费软件，用户可以非商业性地下载、安装、复制和散发本软件产品。本软件不得用于从事违反中国人民共和国相关法律所禁止的活动，<?php echo C("cms_name");?>对于用户擅自使用本软件从事违法活动不承担任何责任（包括但不限于刑事责任、行政责任、民事责任）。如果需要进行商业性的销售、复制和散发，例如软件预装和捆绑，必须获得<?php echo C("cms_name");?>的授权和许可。 

鉴于用户计算机软硬件环境的差异性和复杂性，本软件所提供的各项功能并不能保证在任何情况下都能正常执行或达到用户所期望的结果。用户使用本软件所产生的一切后果，<?php echo C("cms_name");?>不承担任何责任。 

由于本软件产品可以通过网络等途径下载、传播，对于从非<?php echo C("cms_name");?>指定站点下载的本软件产品以及从非<?php echo C("cms_name");?>发行的介质上获得的本软件产品，<?php echo C("cms_name");?>无法保证该软件是否感染计算机病毒、是否隐藏有伪装的特洛伊木马程序或者黑客软件，不承担由此引起的直接和间接损害责任。 

如果用户在安装时选择接受本协议，即表明用户信任<?php echo C("cms_name");?>，自愿选择安装本软件，并接受本协议所有条款。如果用户不接受本协议，不愿安装本软件，请停止安装操作并删除本软件。
    </textarea>
  </div>
  <br />
  <div style="text-align:center;">
    <input type="checkbox" name="setup" id="setup" valeu="1" onClick="if(this.checked){document.getElementById('install').disabled=''}else{document.getElementById('install').disabled='disabled'}" style="border:none">
    <label for="Compact">接受许可协议</label>
    <h5><input id="install" type="submit" class="btn" style="width:120px;height:30px;" value="开始安装" disabled="disabled"></h5>
  </div>
</div>
<div id='msgbottom'>Powered By <?php echo C("cms_name");?> <?php echo C("cms_var");?></div>
</div>
</form>
</body>
</html>