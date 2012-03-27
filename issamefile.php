<?php

$url1 = "http://175.6.0.111:8058/201201/31/20120131102421.jpg";

$url2 = "";

$tmp1 = file_get_contents($url1);
$tmp2 = file_get_contents('b.jpg');
file_put_contents('a.jpg', $tmp1);
//file_put_contents('/b.jpg', $tmp1);

printf("%.2d\n",42);
echo '/';
printf("%1.2f\n",42);
echo '/';
printf("%1.2u\n",42);
echo '/';
echo '<br>';
var_dump(isSameFile('a.jpg','b.jpg'));
echo count('123');


function isSameFile($a,$b){
	if((md5(file_get_contents($a))==md5(file_get_contents($b))&&(filesize($a)==filesize($b)))){
		return true;
	}else{
		return false;
	}

}
//Input limit double-ende queue
class DoubleEndedQueue1 {
var $queue = array();
function add($var){
    return array_push($this->queue, $var);
}
function frontRemove(){
    return array_shift($this->queue);
}
function rearRemove(){
    return array_pop($this->queue);
}
}

//Output limit double-ende queue
class DoubleEndedQueue2 {
var $queue = array();
function remove(){
    return array_pop($this->queue);
}
function frontAdd($var){
    return array_unshift($this->queue, $var);
}
function rearAdd($var){
    return array_push($this->queue, $var);
}
}

//Test code
$q = new DoubleEndedQueue1;
$q->add('aaa');
$q->add('bbb');
$q->add('ccc');
$q->add('ddd');

echo $q->frontRemove();
echo "<br>";
echo $q->rearRemove();
echo "<br>";
print_r($q->queue);




