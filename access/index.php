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
if (ini_get('magic_quotes_gpc')) {
    function stripslashesRecursive(array $array) {
        foreach ($array as $k => $v) {
            if (is_string($v)) {
                $array[$k] = stripslashes($v);
            } else if (is_array($v)) {
                $array[$k] = stripslashesRecursive($v);
            }
        }
        return $array;
    }

    $_GET = stripslashesRecursive($_GET);
    $_POST = stripslashesRecursive($_POST);
}
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
//网站当前路径
define('SITE_PATH', dirname(__DIR__)."/");
if (file_exists(SITE_PATH."config/domain.inc.php")) {
    include SITE_PATH."config/domain.inc.php";
} else {
    exit;
}
ini_set('session.name', 'SSWSDK_ADMINID');
// 加载框架引导文件
require SITE_PATH . 'thinkphp/start.php';
