<?php
echo "部分函数测试";
$t =  "c:/ccc后缀名获取../h.txt";
echo strrchr($t,".");
echo substr($t,strpos($t,"."));
$tarr = array(10,1,2,3,5,9,7,8,5,6,4);
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


?>