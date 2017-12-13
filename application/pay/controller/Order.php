<?php
/**
 * Sdkpay.php UTF-8
 * SDK 游戏支付页面
 *
 * @date    : 2016年11月10日下午6:18:07
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月10日下午6:18:07
 */
namespace app\pay\controller;

use app\common\controller\Basepay;
use think\Session;

class Sdkpay extends Basepay {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 玩家打开支付页面预下单
     */
    function index() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'user_token',
            'timestamp',
            'device_id',
            'userua',
            'cp_order_id',
            'product_price',
            'product_count',
            'product_id',
            'product_name',
            'product_desc',
            'role_type',
            'server_id',
            'server_name',
            'role_id',
            'role_name'
        );
        $_pay_data = $this->getParams($_key_arr);
        Session::set('order', $this->rq_data['orderinfo']);
        Session::set('order_time', time(), 'order');
        Session::set('role', $this->rq_data['roleinfo']);
        $_pay_class = new \sswsdk\pay\Pay();
        // sdk预下单
        $_rdata = $_pay_class->sdkPreorder($_pay_data);
        if (false == $_rdata) {
            $this->error("下单失败");
        }
        $_paytoken = md5(uniqid(hs_random(6)));
        $this->assign('paytoken', rand());
        Session::set('paytoken', $_paytoken, 'order');
        Session::set('order_time', time(), 'order');
        $this->assign($_rdata);
        $_pay_data['amount'] = '10';
        $_pay_data['benefit_type'] = 1;
        $_pay_data['gm_cnt'] = 0;
        $_pay_data['real_amount'] = 0;
        $_pay_data['rebate_cnt'] = 10;
        $_pay_data['order_id'] = 10;
        $this->assign('paydata', $_pay_data);
        $this->assign('productname', 'product_name');
        $ratedata['mem_rate'] = 1;
        $this->assign('ratedata', $ratedata);
        $contactdata = $_pay_class->getcontact();
        $this->assign('contactdata', $contactdata);
        $this->assign('title', "充值中心");
        return $this->fetch();
    }

    /*
     * 玩家选择支付方式 直接下单
     */
    public function pay() {
        $_order_id = $this->request->post('orderid/s');
        $_pay_token = $this->request->post('paytoken/s');
        $_payway = $this->request->post('payway/s');
        $_random = $this->request->post('v/s');
        if (empty($_random)) {
            return hs_pay_responce(0, "非法请求,重复请求");
        }
        $_spr_random = Session::get('pay_random', 'order');
        if ($_spr_random == $_random) {
//             return hs_pay_responce(0,"重复请求");
        }
        Session::set('pay_random', $_random, 'order');
        $_s_paytoken = Session::get('paytoken', 'order');
        if (empty($_order_id) || empty($_pay_token) || $_pay_token != $_s_paytoken) {
            return hs_pay_responce(0, "非法请求,参数错误");
        }
        $_time = time();
        $_order_time = Session::get('order_time', 'order');
        /* 86400s 不支付 订单失效 */
        if ($_order_time + 86400 < $_time) {
            return hs_pay_responce(0, "订单已失效,请重新选择支付");
        }
        $_real_amount = Session::get('real_amount', 'order');
        $_amount = Session::get('product_price', 'order');
        if ($_real_amount <= 0 && $_amount > 0) {
            //全部游戏币支付
        }
        /* 检查支付类型 sswsdktest */
        /* checkPayway; */
        /* 通过支付方式下单 */
        $_p_class = \sswsdk\pay\Driver::init($_payway);
        $_payinfo = $_p_class->clientPay();
        return hs_pay_responce(1, "请求成功", $_payinfo);
    }
}