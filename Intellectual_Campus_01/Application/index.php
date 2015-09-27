<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2015/9/25
 * Time: 10:52
 */
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

//define('BIND_MODULE','Check_Name');

// 引入ThinkPHP入口文件
require '../thinkphp_3.2.3_full/ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单