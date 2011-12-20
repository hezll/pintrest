<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理登录-<?php echo C("cms_name");?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel='stylesheet' type='text/css' href='./views/css/admin_login.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script>
function loginok(form){
	if (form.login_name.value==""){
		alert("用户名不能为空！");
		form.login_name.focus();
		return (false);
	}
	if (form.login_pwd.value==""){
		alert("密码不能为空！");
		form.login_pwd.focus();
		return (false);
	}
	return (true);
}
if(self!=top){
	top.location=self.location;
}
</script>
</head>
<body>
<div class="main">
  <div class="title">&nbsp;</div>
  <div class="login">
    <form action="?s=Admin/Login/Check" method="post" name="gxcms" onSubmit="return loginok(this)">
      <div class="inputbox">
        <dl>
          <dd>用户名：</dd>
          <dd>
            <input type="text" name="login_name" id="login_name" size="25" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" />
          </dd>
          <dd>密码：</dd>
          <dd>
            <input type="password" name="login_pwd" id="login_pwd" size="25" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" />
          </dd>
          <dd>
            <input name="submit" type="submit" value="" class="input" />
          </dd>
        </dl>
      </div>
      <div class="butbox">
        <dl>
          <dd><?php echo C("cms_name");?>是一个采用php和mysql数据库构建的高效影视站解决方案</dd>
        </dl>
      </div>
      <div style="clear:both"></div>
    </form>
  </div>
</div>
<div class="copyright"> 
	Powered by <a href="<?php echo C("cms_url");?>" target="_blank">光线CMS <?php echo C("cms_var");?></a> Copyright &copy;2010-2011 
</div>
<!--请勿删除以下代码 -->
<div style="display:none"><script language="javascript" type="text/javascript" src="http://js.users.51.la/4469144.js"></script></div>
</body>
</html>