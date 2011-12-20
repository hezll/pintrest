<?php
//单入口模式
error_reporting(0);
@set_time_limit(240);
@ini_set("memory_limit",'-1');
require("./core/Conf/define.php");
require(THINK_PATH."/ThinkPHP.php");
$App = new App();
$App->run();
?>