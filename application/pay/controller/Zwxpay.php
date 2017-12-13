<?php
/**
 * Zwxpay.php UTF-8
 * 梓微信支付
 *
 * @date    : 2017年03月30日下午4:25:52
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : ou <ozf@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\pay\controller;

use app\common\controller\Base;

class Zwxpay extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_class = new \sswsdk\pay\Zwxpay();
        $_class->notifyUrl();
    }


    public function returnurl() {
    }
}