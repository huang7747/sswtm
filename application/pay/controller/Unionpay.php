<?php
/**
 * Unionpay.php UTF-8
 *
 * @date    : 2017年03月31日下午4:25:52
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : ou <ozf@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\pay\controller;

use app\common\controller\Base;

class Unionpay extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_class = new \sswsdk\pay\Unionpay();
        $_class->notifyUrl();
    }


    public function returnurl() {
    }
}