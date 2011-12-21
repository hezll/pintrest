<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户中心-<?php echo ($webname); ?></title>
<link rel='stylesheet' type='text/css' href='./views/css/user.css'>
</head>
<body>
<div class="top"><div class="box">
	<div class="logo"><a href="./" title="返回首页"><?php echo ($webname); ?>－用户中心</a></div>
    <div class="info"><?php if(!empty($username)): ?><?php echo (htmlspecialchars($username)); ?>，欢迎您！ <a href="?s=User/Logout">退出</a><?php endif; ?> <a href="./">返回首页</a></div>
</div></div>
<div class="show">
<div class="left">
	<h3><a href="?s=User/Show">用户中心首页</a></h3>
<h4>管理信息</h4>
<ul>
<li><a href="?s=User/Guestbook">留言信息</a></li>
<li><a href="?s=User/Comment">评论信息</a></li>
</ul>
<h4>积分管理</h4>
<ul>
<li><a href="?s=User/Shop">积分充值</a></li>
<li><a href="?s=User/Views">消费记录</a></li>
<li><a href="?s=User/Changepay">消费模式</a></li>
</ul>
<h4>管理帐户</h4>      
<ul>
<li><a href="?s=User/Edit">修改资料</a></li>
<li><a href="?s=User/Logout">退出登录</a></li>
</ul>
</div>
<div class="user">
	<h3>帐号资料</h3>
    <ul class="face"><img src="<?php echo (($face)?($face):'./views/images/user/face.jpg'); ?>" /></ul>
    <ul class="readright">
    <li>用户账号：<?php echo (htmlspecialchars($username)); ?></li>
    <li>电子邮箱：<?php echo (htmlspecialchars($email)); ?></li>
    <li>用户积分：<?php echo ($money); ?></li>
    <li>消费模式：<span style="font-weight:bold;color:red"><?php if(($pay)  ==  "1"): ?>包月<?php else: ?>扣点 (<a href="?s=User/Changepay">我要包月</a>)<?php endif; ?></span></li>
    </ul>
    <h3>详细信息</h3>
    <ul class="readmore">
    <li>联系 QQ：<?php echo (($qq)?($qq):"未填写"); ?></li>
    <li>登录次数：<?php echo ($lognum); ?></li>
 	<li>用户状态：<?php if(($status)  ==  "1"): ?>正常<?php else: ?><font color="red">锁定</font><?php endif; ?></li>   
    <li>密保问题：<?php echo (htmlspecialchars($question)); ?></li>
    <li>包月期限：<?php echo (date('Y-m-d H:i:s',$duetime)); ?></li>
    <li>注册时间：<?php echo (date('Y-m-d H:i:s',$jointime)); ?></li>
    <li>最近登录：<?php echo (date('Y-m-d H:i:s',$logtime)); ?></li>
    <li>最近登录IP：<?php echo ($logip); ?></li>
    </ul>   
</div> 
</div>
</body>
</html>