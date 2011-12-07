<?php
/// 空间信息配置
$username = 'artdoimage';
$password = 'he1234';
$bucket_name = 'artdoimage';

/// write file (with dir)
$data = 'asssss123sssss';
$process = curl_init("http://v0.api.upyun.com/{$bucket_name}/fff/a.txt"); 
curl_setopt($process, CURLOPT_POST, 1);
curl_setopt($process, CURLOPT_POSTFIELDS, $data);
curl_setopt($process, CURLOPT_HTTPHEADER, array('mkdir: true'));
curl_setopt($process, CURLOPT_USERPWD, "{$username}:{$password}");
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
print(curl_exec($process)); 
print(curl_getinfo($process, CURLINFO_HTTP_CODE).'<br/>');
curl_close($process);

/// write file
$data = 'asssss123sssss';
$process = curl_init("http://v0.api.upyun.com/{$bucket_name}/a.txt"); 
curl_setopt($process, CURLOPT_POST, 1);
curl_setopt($process, CURLOPT_POSTFIELDS, $data);
curl_setopt($process, CURLOPT_USERPWD, "{$username}:{$password}");
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
print(curl_exec($process)); 
print(curl_getinfo($process, CURLINFO_HTTP_CODE).'<br/>');
curl_close($process);

/// read file
$process = curl_init("http://v0.api.upyun.com/{$bucket_name}/a.txt"); 
curl_setopt($process, CURLOPT_USERPWD, "{$username}:{$password}");
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
print(curl_exec($process)); 
print(curl_getinfo($process, CURLINFO_HTTP_CODE).'<br/>');
curl_close($process); 
print("==============================================================\n");
die();


/// get bucket usage
$process = curl_init("http://v0.api.upyun.com/{$bucket_name}/?usage"); 
curl_setopt($process, CURLOPT_USERPWD, "{$username}:{$password}");
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
print(curl_exec($process)); 
print(curl_getinfo($process, CURLINFO_HTTP_CODE).'<br/>');
curl_close($process); 
print("==============================================================\n");
die();


/// delete file
$process = curl_init("http://v0.api.upyun.com/{$bucket_name}/a"); 
curl_setopt($process, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($process, CURLOPT_USERPWD, "{$username}:{$password}");
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
print(curl_exec($process)); 
print(curl_getinfo($process, CURLINFO_HTTP_CODE).'<br/>');
curl_close($process); 
print("==============================================================\n");
/*die();*/

/// mkdir
$process = curl_init("http://v0.api.upyun.com/{$bucket_name}/a"); 
curl_setopt($process, CURLOPT_POST, 1);
curl_setopt($process, CURLOPT_POSTFIELDS, '');
curl_setopt($process, CURLOPT_HTTPHEADER, array('folder: true'));
curl_setopt($process, CURLOPT_USERPWD, "{$username}:{$password}");
curl_setopt($process, CURLOPT_HEADER, 0); 
curl_setopt($process, CURLOPT_TIMEOUT, 30); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
print(curl_exec($process)."<br/>"); 
print(curl_getinfo($process, CURLINFO_HTTP_CODE).'<br/>');
curl_close($process);
print("==============================================================\n");
