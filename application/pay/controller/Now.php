<?php
/**
 * Now.php UTF-8
 * 现在支付对外函数
 *
 * @date    : 2017年2月8日14:40:17
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wangchuang <wc@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2017年2月8日14:40:41
 */
namespace app\pay\controller;

use app\common\controller\Base;
use think\Log;

class Now extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_class = new \sswsdk\pay\Now();
        $_class->notifyUrl();
    }

    public function returnurl() {
    }
}