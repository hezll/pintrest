<?php if (!defined('THINK_PATH')) exit();?><!--<link rel='stylesheet' type='text/css' href='./views/css/admin_style.css'> -->
<style>
body {margin:0px;padding:0px;font-size:12px;color:#313131;font-family: "sim-sun", "Geneva", "Arial", "Helvetica", "sans-serif";}
input {border:1px solid #bbb;height:22px;vertical-align:middle;font-size:12px;}
</style>
<form action="?s=Admin/Upload/Upload" method="post" enctype="multipart/form-data" id="gxform" name="gxform" style="margin-left:5px">
<input name="mid" type="hidden" value="<?php echo (htmlspecialchars($_GET['mid'])); ?>"/>
<input name="fileback" type="hidden" value="<?php echo (htmlspecialchars($_GET['fileback'])); ?>"/>
<input type="file" name="upthumb" id="upthumb" size="25"> <input type="submit" value="上 传" style="background:url(../images/admin/inputbut_bg.gif); cursor:pointer"/>
{__NOTOKEN__}<!--onclick="if(!upthumb.value){alert('请选择要上传的文件');return false;}" -->
</form>