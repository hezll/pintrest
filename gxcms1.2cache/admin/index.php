<?php
/**
 * @name admin check
 * @time 2011-8-10
 */
session_start();
$_SESSION['right_enter'] = 1;
header("Location: ../index.php?s=Admin/Login"); 
?>