<?php
//单入口模式
define('THINK_MODE','Cli');
@set_time_limit(240);
@ini_set("memory_limit",'-1');

define('THINK_PATH', dirname(__FILE__).'/core/ThinkPHP');
define('RUNTIME_PATH','./temp/');
define('APP_PATH',dirname(__FILE__));
define('APP_NAME', 'gxcms');

require(THINK_PATH."/ThinkPHP.php");
$App = new App();
$App->run();
?>