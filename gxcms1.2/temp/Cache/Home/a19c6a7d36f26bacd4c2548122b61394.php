<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>消费记录-<?php echo ($webname); ?></title>
<link rel='stylesheet' type='text/css' href='./views/css/user.css'>
</head>
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
    <h3><span style="float:right">共<?php echo ($count_view); ?>条记录</span>我的消费记录</h3> 
    <?php if(is_array($list_view)): $i = 0; $__LIST__ = $list_view;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gxcms): ++$i;$mod = ($i % 2 )?><ul style="margin-bottom:10px">
    <li><?php echo ($gxcms["floor"]); ?> <a href="<?php echo get_read_url('video',$gxcms['did'],$gxcms['cid']);?>" target="_blank"><?php echo (htmlspecialchars($gxcms["title"])); ?></a> <?php echo (date('Y-m-d H:i:s',$gxcms["viewtime"])); ?></li>
    </ul><?php endforeach; endif; else: echo "" ;endif; ?> 
    <ul class="pages"><?php if(!empty($list_view)): ?><div class="comm-pager"><?php echo ($pages); ?></div><?php else: ?>没有消费记录。<?php endif; ?></ul>
</div>	
</div>
</body>
</html>