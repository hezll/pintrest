<?php
echo 8%(-2);

$file = fopen("pra.php","w+");

if(flock($file,LOCK_EX)){
    fwrite($file,"write something");
    flock($file,LOCK_UN);
}else{
    echo "Error locking file!";
}







?>