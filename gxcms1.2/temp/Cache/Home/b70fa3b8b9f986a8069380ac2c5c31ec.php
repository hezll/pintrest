<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心-登录页-<?php echo ($webname); ?></title>
<link rel='stylesheet' type='text/css' href='./views/css/user.css'>
<script language="JavaScript">if(self!=top){top.location=self.location;}</script>
</head>
<body>
<div class="login">
	<form action="index.php?s=User/Check" method="post" name="gxcms" id="gxcms">
    <input name="jumpurl" type="hidden" value="<?php echo (htmlspecialchars(trim($_SERVER['HTTP_REFERER']))); ?>">
	<div class="left"><span>用户登录</span></div>
    <div class="right"><ul class="inputbox">
    <li>帐户名：<input name="username" type="text" onfocus="if(this.value =='用户名/电子邮箱')this.value='';this.style.color='#333';this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" maxlength="50" value="用户名/电子邮箱" style="color:#CCC"/></li>
    <li>密&nbsp;&nbsp;&nbsp;码：<input name="userpwd" type="password" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" /></li>
    <li><input type="submit" name="" class="input" value=""/> <a href="index.php?s=User/Reg">免费注册</a> | <a href="index.php?s=User/Repass">忘记密码</a></li>
    </ul></div>
    </form>
</div>
</body>
</html>