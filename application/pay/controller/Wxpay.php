<?php
/**
 * Wxpay.php UTF-8
 * 微信支付回调地址
 *
 * @date    : 2017/6/7 20:52
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : HUOOA 1.0
 */
namespace app\pay\controller;

use app\common\controller\Base;

class Wxpay extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_class = new \sswsdk\pay\Wxpay();
        $_class->notifyUrl();
    }

    public function returnurl() {
    }
}