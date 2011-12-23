<?php
header("Content-type: text/html; charset=utf-8");
//http://item.taobao.com/item.htm?id=13152986793&wwdialog=bbxxbbmc
$url = "http://www.eaidc.com/ba.html";
$ch  = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
curl_close($ch);
$arr_data = explode('<br />', $data);
array_shift($arr_data);


//http://item.taobao.com/item.htm?id=7627611524&wwdialog=bbxxbbmc  赵云18
$datatxt = '';
foreach (glob("yuming/*.txt") as $k=>$filename) {
    $datatxt = file_get_contents($filename);
     $arr_tmp = explode('\n', $datatxt);
var_dump($arr_tmp);
echo count($arr_tmp);exit;
}
 
exit;
//$arr_data = explode('com', $datatxt);
//var_dump($arr_data);

exit;
//sort($arr_data);
usort($arr_data,'cmp');

function cmp($a, $b)
{
    if (strlen($a) == strlen($b)) {
        return 0;
    }
    return (strlen($a) < strlen($b)) ? -1 : 1;
}

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

foreach($arr_data as $val){
        if(strpos($val,'.net')===false){
        //echo strpos('.net',$k);
            echo $val.'<br>';
        }
       
}

