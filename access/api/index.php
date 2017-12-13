<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('ROOT_SITE_PATH', dirname(dirname(__DIR__)).'/');
define('SITE_PATH', ROOT_SITE_PATH);

define('APP_PATH', SITE_PATH.'application/');

// 定义配置文件目录和应用目录同级
define('CONF_PATH', SITE_PATH.'config/');

// 加载框架引导文件
//require __DIR__ . '/../thinkphp/start.php';
require SITE_PATH.'thinkphp/start.php';
