<?php
/**
 * Ysepay.php UTF-8
 * 银盛支付对外接口
 *
 * @date    : 2017/3/29 23:14
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\pay\controller;

use app\common\controller\Base;
use sswsdk\pay\Ysepay as YsepayInner;

class Ysepay extends Base {
    function _initialize() {
        parent::_initialize();
    }

    public function notifyurl() {
        $_ali_class = new YsepayInner();
        $_ali_class->notifyUrl();
    }

    public function returnurl() {
        $_ali_class = new YsepayInner();
        $_rs = $_ali_class->returnUrl();
        $_data['paytype'] = 'ysepay';
        $_data['orderid'] = $_POST['out_trade_no'];
        if ($_rs) {
            $_data['status'] = 2;
            $_data['info'] = '银联支付成功';
        } else {
            $_data['status'] = 3;
            $_data['info'] = '银联支付失败';
        }
        $_rdata = json_encode($_data);
        $html
            = <<<  EOT
<script language='javascript'>
function ysepay_huopay(data) {
    var txt = data + '';
    window.sswsdk.huoPay(txt);
}
ysepay_huopay($_rdata);
</script>
EOT;
        echo $html;
        exit;
    }
}