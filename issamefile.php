<?php

$url1 = "http://175.6.0.111:8058/201201/31/20120131102421.jpg";

$url2 = "";

$tmp1 = file_get_contents($url1);
$tmp2 = file_get_contents('b.jpg');
file_put_contents('a.jpg', $tmp1);
//file_put_contents('/b.jpg', $tmp1);

var_dump(isSameFile('a.jpg','b.jpg'));



function isSameFile($a,$b){
	if((md5(file_get_contents($a))==md5(file_get_contents($b))&&(filesize($a)==filesize($b)))){
		return true;
	}else{
		return false;
	}

}




