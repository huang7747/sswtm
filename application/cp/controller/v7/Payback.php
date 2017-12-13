<?php
/**
 * Payback.php UTF-8
 * CP支付回调测试
 *
 * @date    : 2016年11月18日下午4:00:47
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月18日下午4:00:47
 */
namespace app\cp\controller\v7;

use think\Controller;
use think\Session;

class Payback extends Controller {
    function _initialize() {
        parent::_initialize();
//        $_POST = array(
//            'app_id'        => '1',
//            'cp_order_id'   => '20161028111',
//            'mem_id'        => '',
//            'order_id'      => '14794570065945404260001',
//            'order_status'  => '2',
//            'pay_time'      => '1479457006',
//            'product_id'    => '1',
//            'product_name'  => '元宝',
//            'product_price' => '1',
//            'sign'          => 'dc8258438db62e76d2cbde46ce408e92',
//            'ext'           => '穿透',
//        );
    }

    public function notify() {
        /* 1 查询是否具有访问权限 */
        $_urldata = $this->request->param();
        \think\Log::write($_urldata, 'error');//记录请求数据
        $this->checkAuth();
        $_data['app_id'] = $this->request->param('app_id');
        $_data['cp_order_id'] = $this->request->param('cp_order_id');
        $_data['mem_id'] = $this->request->param('mem_id');
        $_data['order_id'] = $this->request->param('order_id');
        $_data['order_status'] = $this->request->param('order_status');
        $_data['pay_time'] = $this->request->param('pay_time');
        $_data['product_id'] = $this->request->param('product_id');
        $_data['product_name'] = $this->request->param('product_name');
        $_data['product_price'] = $this->request->param('product_price');
        $_sign = $this->request->param('sign');
        $_ext = $this->request->param('ext');
        $_veri_str = http_build_query($_data);
        $_app_key = $this->checkAppid($_data['app_id']);
        if (empty($_app_key)) {
            die('FAILURE');
        }
        $_veri_sign = md5($_veri_str.'&app_key='.$_app_key);
        if ($_sign != $_veri_sign) {
            die('FAILURE');
        }
        die('SUCCESS');
    }

    private function checkAppid($app_id) {
        $_g_class = new \sswsdk\game\Game();
        $_game_info = $_g_class->getGameinfo($app_id);
        if (empty($_game_info)) {
            return false;
        }

        return $_game_info['app_key'];
    }

    private function checkAuth() {
        return true;
    }
}