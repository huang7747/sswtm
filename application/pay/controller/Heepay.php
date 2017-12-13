<?php
/**
 * Heepay.php UTF-8
 * 支付宝对外函数
 *
 * @date    : 2016.12.14
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @version : sswsdk 7.0
 * @modified: 2016.12.14
 */
namespace app\pay\controller;

use app\common\controller\Base;
use think\Log;

class Heepay extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        //Log::write($_POST, 'error'); // 记录请求数据
        $_hee_class = new \sswsdk\pay\Heepay();
        $_hee_class->notifyUrl();
    }

    public function returnurl() {
    }
}