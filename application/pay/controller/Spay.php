<?php
/**
 * Spay.php UTF-8
 * 微付通对外函数
 *
 * @date    : 2016年11月18日下午4:25:52
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月18日下午4:25:52
 */
namespace app\pay\controller;

use app\common\controller\Base;

class Spay extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_class = new \sswsdk\pay\Spay();
        $_class->notifyUrl();
    }

    public function returnurl() {
        $_class = new \sswsdk\pay\Spay();
        $_info = $_class->returnUrl();
        $_msg = "亲，恭喜您支付成功，请点击关闭按钮关闭！";
        $_de_info = json_decode($_info, true);
        if ("1" == $_de_info['status']) {
            $_msg = "亲，您支付失败了，请点击关闭按钮重试！";
        }
        $this->assign('info', $_info);
        $this->assign('msg', $_msg);

        return $this->fetch();
    }
}