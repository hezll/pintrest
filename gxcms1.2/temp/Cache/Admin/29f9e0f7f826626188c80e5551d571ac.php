<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<title>站点安装-许可协议</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/install.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
</head>
<body >
<div id='installbox'>
<div class='msgtitle'><?php echo C("cms_name");?><?php echo C("cms_var");?> 安装向导</div>
<table width="573" height="23" border="0" cellpadding="0" cellspacing="0" style="text-align:center; font-weight:bold;font-size:12pt;background:url(views/images/install/set03_top_nav.gif); margin:10px 0 0 10px;">
  <tr>
    <td style="color:#666; text-align:center"><span style="display:block; float:left; width:20%; font-size:12px;">1. 许可协议</span><span style="display:block;float:left;width:25%;font-size:12px;">2. 环境检测</span><span style="display:block; float:left; width:25%; font-size:12px; color: #FFF;">3. 数据库设置</span><span style="display:block;float:left;width:25%;font-size:12px;">4. 安装完成</span></td>
  </tr>
</table>
<h3>安装设置：</h3>
<form  method="post" name="myform" id="gxform" style="margin:0; padding:0;">
<table width="98%" border="0" align="center" cellpadding="5" cellspacing="1" class="tableoutline" style="font-size:12px; color:#666;">
<input name="setup" type="hidden" value="<?php echo $_POST["setup"];?>">
   <tr bgcolor="#F4faff">
    <td width="48%" valign="top"><b>安装目录</b><br><font color="#666666">自动检测一般不需要修改,结尾必需加斜杆 '/'</font></td>
 <td><input type="text" name="con[web_path]" id="web_path" value="<?php echo get_base_path('index.php');?>" maxlength="50" style="width:250px;" > *</td>
  </tr>
  <tr class="firstalt">
    <td width="48%" valign="top" bgcolor="#FFFFFF"><b>服务器地址</b><br>
      <font color="#666666">一般为localhost</font></td>
    <td bgcolor="#FFFFFF"><input type="text" name="con[db_host]" id="db_host" value="localhost" maxlength="50" style="width:250px;" > *</td>
  </tr>
  <tr bgcolor="#F4faff">
    <td width="48%"><b>数据库端口</b><br><font color="#666666">请填写MYSQL数据库使用的端口</font><br></td>
    <td><input type="text" name="con[db_port]" id="db_port" value="3306" maxlength="50" style="width:250px;" > *</td>
  </tr>                   
  <tr class="firstalt">
    <td width="48%"><b>数据库用户名</b><br><font color="#666666">一般为root</font><br></td>
    <td><input type="text" name="con[db_user]" id="db_user" value="root" maxlength="50" style="width:250px;"> *</td>
  </tr>
  <tr bgcolor="#F4faff">
    <td width="48%" bgcolor="#F4faff"><b>数据库密码</b><br>密码尽量不要设为空<br></td>
    <td bgcolor="#F4faff"><input type="password" name="con[db_pwd]" id="db_pwd" maxlength="50" style="width:250px;" ></td>
  </tr>
  <tr class="firstalt">
    <td width="48%"><b>数据库名称</b><br><font color="#666666">请填写已存在的数据库名</font><br></td>
    <td bgcolor="#FFFFFF"><input type="text" name="con[db_name]" id="db_name" value="gxcms" maxlength="50" style="width:250px;"> *</td>
  </tr>  
  <tr bgcolor="#F4faff" class="firstalt">
    <td width="48%"><p><b>数据库表前缀</b><br><font color="#666666">一般不用修改</font><br></p></td>
    <td><input type="text" name="con[db_prefix]" id="db_prefix" value="gx_"  maxlength="50"  valid="required"  style="width:250px;" > *</td>
  </tr>
  </table>
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr class="firstalt" style="height:10px">
        <td height="70" colspan="2" align="center">
        <input style="width:120px; height:30px;" type="button" class="btn"  value="<< 上一步" onClick="history.back();"/> 
        <input style="width:120px; height:30px;" type="submit" name="submit" value="下一步 >>" class="btn" id="submit"> 
        <span id="loading" style="font-size:13px;color:#FF0000;display:none"><font color="#0066CC">请稍等...正在与MYSQL数据库进行连接。</font>
        </span>
        </td>
    </tr>
      <tr class="firstalt" style="height:10px">
        <td colspan="2" align="center"><div id='msgbottom'>Powered By <?php echo C("cms_name");?> <?php echo C("cms_var");?> </div></td>
      </tr>
  </table>
</form>
  </div>
</div>
<script language="javascript" charset="utf-8">
$("#gxform").submit(function(){
	var $dbname = $('#db_host').val();
	$("#loading").ajaxStart(function(){
		$(this).show();
	});
	$.ajax({
		type:'post',
		url: '?s=Admin/Install/Setup',
		data:'con[web_path]='+$('#web_path').val()+'&con[db_host]='+$('#db_host').val()+'&con[db_port]='+$('#db_port').val()+'&con[db_name]='+$('#db_name').val()+'&con[db_user]='+$('#db_user').val()+'&con[db_pwd]='+$('#db_pwd').val()+'&con[db_prefix]='+$('#db_prefix').val()+'',
		timeout: 10000,
		dataType:'text',
		error: function () {
			$("#loading").html('请检查MYSQL是否正常运行!');
		},
		success:function(res){
			if(res == 'ok'){
				location.href = '?s=Admin/Install/Setupend';
			}else{
				$("#loading").html(res);
			}		
		}
    });
	return false;
});
</script>
</body>
</html>