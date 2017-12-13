<?php
/**
 * Gamemoney.php UTF-8
 * 游戏币操作对外接口
 *
 * @date    : 2017/2/23 16:18
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use app\common\controller\Basehuo;
use sswsdk\coupon\Coupon;
use sswsdk\integral\Integral;
use sswsdk\wallet\Wallet;
use think\Config;
use think\Session;

class Gamemoney extends Basehuo {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 【内】充值页面数据获取
     * http://doc.1tsdk.com/43?page_id=805
     */
    function index() {
        $_mem_id = $this->isUserLogin();
        $_key_arr = array(
            'app_id',
            'client_id',
            'from'
        );
        $_param_data = $this->getParams($_key_arr);
        $_game_id = $_param_data['gameid'];
        $_money = $_param_data['money'];
        $_couponcontent = $_param_data['couponcontent'];
        if (empty($_game_id)) {
            return hs_sswsdk_responce('400', '游戏参数错误');
        }
        Session::set('game_id', $_game_id, 'app'); /* 充值游戏币 游戏 */
        if (empty($_money)) {
            return hs_sswsdk_responce('400', '金额参数错误');
        }
        /* 获取我的代金卷 */
        $_coupon_class = new Coupon();
        $_max_rate = $_coupon_class->getMaxrate();
        $_remain_money = $this->checkCoupon($_mem_id, $_money, $_couponcontent, $_max_rate);
        if (false == $_remain_money) {
            return hs_sswsdk_responce(400, '代金卷错误');
        }
        $_rdata = $_coupon_class->getMemlist($_mem_id);
        $_rdata['rate'] = Wallet::getRate();
        if (empty($_rdata['rate'])) {
            $_rate_array = Config::get('config.wallet');
            $_rdata['rate'] = $_rate_array['rate'];
        }
        $_itg_class = new Integral($_mem_id);
        $_rdata['integral'] = $_itg_class->getItgbyMoney($_remain_money);
        $_rdata['maxcoupon'] = $_coupon_class->getMemCouponWeal($_mem_id);
        $_max_coupon = floor($_money * $_max_rate);
        if ($_rdata['maxcoupon'] > $_max_coupon) {
            $_rdata['maxcoupon'] = $_max_coupon;
        }

        return hs_sswsdk_responce(200, '请求成功', $_rdata, $this->auth_key);
    }

    public function checkCoupon($_mem_id, $money, $coupon_content, $max_rate = 1) {
        /* 查询代金卷金额 */
        $_coupon_class = new Coupon();
        $_c_money = $_coupon_class->getMoneybyString($_mem_id, $coupon_content);
        if (empty($_c_money)) {
            $_c_money = 0;
        }
        if ($_c_money > $money * $max_rate) {
            return false;
        }

        return number_format($money - $_c_money, 2, '.', '');
    }

    /**
     *【内】游戏币充值预下单(preorder)
     * http://doc.1tsdk.com/43?page_id=689
     */
    public function preorder() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'user_token',
            'timestamp',
            'device_id',
            'userua',
            'money',
            'real_money'
        );
        $_pay_data = $this->getParams($_key_arr);
        $_mem_id = Session::get('id', 'user');
        $_money = $_pay_data['money'];
        $_couponcontent = $_pay_data['couponcontent'];
        $_coupon_class = new Coupon();
        $_max_rate = $_coupon_class->getMaxrate();
        $_remain_money = $this->checkCoupon($_mem_id, $_money, $_couponcontent, $_max_rate);
        if (false == $_remain_money) {
            return hs_sswsdk_responce(400, '代金卷错误');
        }
        Session::set('product_price', $_money, 'order');
        Session::set('product_name', "充值游戏币", 'order');
        Session::set('product_desc', "充值游戏币", 'order');
        Session::set('real_amount', $_remain_money, 'order');
        Session::set('order_time', time(), 'order');
        Session::set('gm_cnt', $_money * Wallet::getRate(), 'order');
        $_pay_class = Wallet::init();
        $_rs = $_pay_class->preorder(PAYFROM_APP, $_couponcontent);
        if (false == $_rs) {
            $this->error("下单失败");
        }
        $_paytoken = md5(uniqid(hs_random(6)));
        Session::set('paytoken', $_paytoken, 'order');
        $_rdata = $this->getPayways();
        $_rdata['order_id'] = Session::get('order_id', 'order');
        $_rdata['real_amount'] = $_remain_money;
        $_rdata['paytoken'] = $_paytoken;

        return hs_sswsdk_responce('200', '获取成功', $_rdata, $this->auth_key);
    }

    public function getPayways($app_id = 0) {
        $_rdata['count'] = 2;
        $_list = [
            [
                'paytype' => 'alipay',
            ],
            [
                'paytype' => 'spay',
            ]
        ];
        $_rdata['list'] = $_list;

        return $_rdata;
    }

    /**
     * 【内】游戏币充值支付(gamemoney/pay)
     * http://doc.1tsdk.com/43?page_id=689
     */
    public function pay() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'user_token',
            'timestamp',
            'device_id',
            'userua',
            'orderid',
            'paytoken',
            'paytype'
        );
        $_pay_data = $this->getParams($_key_arr);
        $_order_id = $_pay_data['orderid'];
        $_payway = $_pay_data['paytype'];
        $_paytoken = $_pay_data['paytoken'];
        $_s_paytoken = Session::get('paytoken', 'order');
        if ($_paytoken != $_s_paytoken) {
            return hs_sswsdk_responce(400, "非法请求,参数错误");
        }
        if ($_order_id != Session::get('order_id', 'order')) {
            return hs_sswsdk_responce(400, "订单已失效，请重新下单");
        }
        $_time = time();
        $_order_time = Session::get('order_time', 'order');
        /* 86400s 不支付 订单失效 */
        if ($_order_time + 86400 < $_time) {
            return hs_sswsdk_responce(400, "订单已失效,请重新选择支付");
        }
        $_pay_class = Wallet::init();
        $_rs = $_pay_class->upPayway($_order_id, $_payway);
        if (!$_rs) {
            return hs_sswsdk_responce(1000, "订单不存在");
        }
        /* 通过支付方式下单 */
        $_pay_class = \sswsdk\pay\Driver::init($_payway);
        $_payinfo = $_pay_class->clientPay();
        if (empty($_payinfo)) {
            return hs_sswsdk_responce(400, "下单失败");
        }

        return hs_sswsdk_responce(200, "请求成功", $_payinfo, $this->auth_key);
    }
}