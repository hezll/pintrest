<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户注册-<?php echo ($webname); ?></title>
<link rel='stylesheet' type='text/css' href='./views/css/user.css'>
<script language="JavaScript" charset="utf-8" type="text/javascript" src="./views/js/jquery.js"></script>
<script language="JavaScript">
	if(self!=top){top.location=self.location;}
	function showagree(){
		var offset = $("#syxy").offset();
		var left = offset.left/2;
		var top = offset.top/2;
		$("#showagree").toggleClass("regnone"); 
		$("#showagree").css({left:left,top:top,display:""});
	}
	function closereg(){
		$("#showagree").hide();
	}
	function checkform(){
		if(!$('#username').val()){
			alert($('#username').attr('error'));
			return false;
		}
		if(!$('#email').val()){
			alert($('#email').attr('error'));
			return false;
		}
		if(!$('#userpwd').val()){
			alert($('#userpwd').attr('error'));
			return false;
		}
		if($("#userpwd").val() != $("#reuserpwd").val()){
			alert($('#reuserpwd').attr('error'));
			return false;
		}
		if(!$('#question').val()){
			alert($('#question').attr('error'));
			return false;
		}
		if(!$('#answer').val()){
			alert($('#answer').attr('error'));
			return false;
		}				
	}
</script>
</head>
<body>
<div class="login" style="margin-top:100px">
	<form action="?s=User/Insert" method="post" name="gxcms" id="gxcms" onSubmit="return checkform();">
    <input name="jumpurl" type="hidden" value="<?php echo (htmlspecialchars(trim($_SERVER['HTTP_REFERER']))); ?>">
	<div class="regleft"><span>用户注册</span></div>
    <div class="right"><ul >
    <li class="reginput">帐户名：<input name="username" id="username" type="text" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" maxlength="12" error="帐户名不能为空" title="不超过12个字符"/></li>
    <li class="reginput">邮&nbsp;&nbsp;&nbsp;箱：<input name="email" id="email" type="text" maxlength="50" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" error="邮箱不能为空" title="不超过50个字符"/></li>
    <li class="reginput">密&nbsp;&nbsp;&nbsp;码：<input name="userpwd" id="userpwd" type="password" maxlength="15" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" error="密码不能为空" title="不超过15个字符"/></li>
    <li>确认密码：<input name="reuserpwd" id="reuserpwd" type="password" maxlength="15" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" error="两次输入的密码不一样"/></li>
    <li>密保问题：<input name="question" id="question" type="text" maxlength="20" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" error="请输入密保问题" title="不超过20个字符"/></li>
    <li>密保答案：<input name="answer" id="answer" type="text" maxlength="20" onfocus="this.style.borderColor='#fc9938'" onblur="this.style.borderColor='#dcdcdc'" error="请输入密保答案" title="不超过20个字符"/></li>
    <li style="padding-left:60px"><input name="agree" type="checkbox" checked="checked" style="border:none;background:none" /><span class="f12">我确认我同意</span></label> <a href="javascript:void(0);" id="syxy" onclick="showagree();">使用协议</a></li>
    <li style="padding-left:35px"><input type="submit" name="" class="input input2" value=""/> 已经是会员，请<a href="index.php?s=User/Login">登录</a></li>
    </ul></div>
    </form>
</div>
<style>.regnone{display:none}</style>
<div id="showagree" style="position:absolute;background: #F2F2F2;border:2px solid #ccc;padding:5px; width:400px;height:280px;" class="regnone"><h4 style="font-size:14px;color:#000000; height:25px; line-height:25px"><span style="float:left"><?php echo C("web_name");?>使用协议:</span><span style=" float:right;cursor:pointer" onclick="closereg();">关闭</span></h4>
<textarea style="width:100%; height:250px;">
注册用户的义务
  (1) 遵守《全国人大常委会关于维护互联网安全的决定》、《互联网电子公告服务管理规定》及中华人民共和国其他各项有关法律、法规，承担一切因您的行为直接或间接引起的民事或刑事法律责任。
  (2) 尊重网上道德，严禁发表危害国家安全、破坏民族团结、破坏国家宗教政策、破坏社会稳定、侮辱、诽谤、教唆、虚假、淫秽等内容的作品。
  (3) 注册时提供您本人真实、正确、最新及完整的资料，并负责进行更新，以确保其真实、正确、最新及完整。
  (4) 注册用户自行负担上网所需的设备及费用。
  (5) 在任何情况下，注册用户不得利用“<?php echo C("web_name");?>”进行违反或可能违反国家法律和法规的言论或行为，否则，“<?php echo C("web_name");?>”可在任何时候不经任何事先通知终止向您提供服务。并且用户对自己的言论或行为负责。
若您提供任何错误、不实、过时或不完整的资料或信息，并为“<?php echo C("web_name");?>”所确知，或者“<?php echo C("web_name");?>”有合理的理由怀疑前述资料或信息为错误、不实、过时或不完整，“<?php echo C("web_name");?>”有权暂停或终止您的帐号，并拒绝您于现在和未来使用“<?php echo C("web_name");?>”全部或部分的服务。
</textarea></div>
</body>
</html>