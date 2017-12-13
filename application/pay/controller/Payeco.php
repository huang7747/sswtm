<?php
/**
 * Payeco.php UTF-8
 * 易联支付对外函数
 *
 * @date    : 2016年11月18日下午11:56:16
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月18日下午11:56:16
 */
namespace app\pay\controller;

use app\common\controller\Base;
use think\Log;

class Payeco extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_class = new \sswsdk\pay\Payeco();
        $_class->notifyUrl();
    }

    public function returnurl() {
    }
}