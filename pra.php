<?php
header("Content-Type:text/html;charset=UTF-8");
echo "部分函数测试";
$t =  "c:/ccc后缀名获取../h.txt";
echo strrchr($t,".");
echo substr($t,strpos($t,"."));
$tarr = array(10,1,2,3,5,9,7,8,5,6,4,3);
//冒泡排序
function buble_sort($arr){
    $n = count($arr);
    
    for($i=0;$i<$n;$i++){

        for($j = $n-1;$j>$i;$j--){
           
            if($arr[$j]<$arr[$j-1]){
                $tmp = $arr[$j];
                $arr[$j] = $arr[$j-1];
                $arr[$j-1] = $tmp;
                $flag = false;
            }
        }
    }
    return $arr;
}

//快速排序
function quick_sort($arr){
    $n = count($arr);
    if($n<=1) return $arr;
    $key = $arr[0];
    $left_arr = array();
    $right_arr = array();
    for($i = 1;$i<$n;$i++)
    {
        if($arr[$i]<=$key)
            $left_arr[] = $arr[$i];
        else
            $right_arr[] = $arr[$i];
    }
    $left_arr = quick_sort($left_arr);
    $right_arr = quick_sort($right_arr);
    return array_merge($left_arr,array($key),$right_arr);
}
$rs = quick_sort($tarr);
var_dump($rs);



//二分查找
function bin_sch($arr,$low,$high,$k){
    if($low<$high){
        $mid = intval(($low+$high)/2);
        if($arr[$mid] ==$k){
            return $mid;
        }else if($arr[$mid]<$k){
            bin_sch($arr,$low,$mid-1);
        }else{
            bin_sch($arr,$mid+1,$high);
        }
    }else{
        return -1;
    }
    
}

//顺序查找
function seq_sch($arr,$n,$k){
    $arr[$n] = $k;
    for($i = 0;$i<$n;$i++){
        if($arr[$i]==$k)
            break;
    }
    if($i<$n){
        return $i;
    }else{
        return -1;
    }

}

//var_dump(bin_sch($tarr,6,9,5));

var_dump(bin_sch($rs,0,10,5));

var_dump(seq_sch($rs,5,5));


function get_count(){
    static $count = 0;
    return ++$count;
    return $count++;
}
echo get_count();
echo get_count();

$GLOBALS['var1'] = 5;
$var2 = 1;

function get_value(){
    global $var2;
    $var1 = 0;
    return $var2++;
}
echo "<hr/>";

get_value();
echo $var1;
echo $var2;

function get_arr($arr){
var_dump($arr);
    unset($arr[0]);
    var_dump($arr);
}
    $arr1 = array(1,2);
    $arr2 = array(1,2);
    get_arr(&$arr1);
    get_arr($arr2);
    echo count($arr1);
    echo count($arr2);
$a5 = array(array());    
echo   empty($a5)?'true':'false';

echo "<hr/>";
//取后缀名
$t = 'dir/upload.image.jpg';
function get_ex1($f){
    return end(explode(".",$f));    
}
echo get_ex1($t);

function get_ex2($f){
    var_dump(strrchr($f,"."));
}
get_ex2($t);

function get_ex3($f){
    return substr($f,strrpos($f,"."));
}
echo get_ex3($t);
function get_ex4($f){
    $p = pathinfo($f);
    return isset($p['extension'])?$p['extension']:false;
}
echo get_ex4($t);

function get_ex5($f){
    return strrev(substr(strrev($f),0,strpos(strrev($f),".")));
}

echo get_ex5($t);

$tarr = array("xxx","cc","dd","cc");
foreach($tarr as $val){
    $xx[$val]++;
}
rsort($xx);
var_dump($xx);

function bianli($dir)
{
 if(!is_dir($dir)) return;
 $files = array();
 if($handle = opendir($dir)){
  while(false !== ($file = readdir($handle))){
   if($file != '.' && $file != '..'){
    if(is_dir($dir.'/'.$file)){
     $files[$file] = bianli($dir.'/'.$file);
    }else {
     $files[] = $file;
    } 
   }
  }
 }
 closedir($handle);
 return $files;
}

echo "<hr/>";


echo count("abc");

?>