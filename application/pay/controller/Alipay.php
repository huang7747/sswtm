<?php
/**
 * Alipay.php UTF-8
 * 支付宝对外函数
 *
 * @date    : 2016年11月18日下午4:25:29
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月18日下午4:25:29
 */
namespace app\pay\controller;

use app\common\controller\Base;

class Alipay extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_ali_class = new \sswsdk\pay\Alipay();
        $_ali_class->notifyUrl();
    }

    public function returnurl() {
        $_ali_class = new \sswsdk\pay\Alipay();
        $_info = $_ali_class->returnurl();
        $_msg = "亲，恭喜您支付成功，请点击关闭按钮关闭！";
        if ("3" == $_info['status']) {
            $_msg = "亲，您支付失败了，请点击关闭按钮重试！";
        }
        $this->assign('info', $_info);
        $this->assign('msg', $_msg);

        return $this->fetch();
    }

    public function showurl() {
        return $this->fetch();
    }
}