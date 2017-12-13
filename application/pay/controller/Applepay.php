<?php
/**
 * Applepay.php UTF-8
 * 苹果支付下单 验单
 *
 * @date    : 2016年12月20日下午4:20:49
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月20日下午4:20:49
 */
namespace app\pay\controller;

use app\common\controller\Baseplayer;
use think\Session;

class Applepay extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 玩家打开支付页面预下单
     */
    function preorder() {
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
        //获取切换状态
        $_pay_switch = $_pay_class->getPayswitch($_pay_data['app_id']);
        $_rdata['pay_switch'] = $_pay_switch;
        //如果是需要切换，直接返回
        if (1 == $_pay_switch) {
            $_mem_info = array();
            $_sw_rs = $this->isSwitch($_mem_info, $_pay_data);
            Session::set('isSwitch', $_sw_rs, 'order');
            if (0 != $_sw_rs) {
                $_rdata['order_id'] = "";
                $_rdata['paytoken'] = "";

                return hs_player_responce('201', '下单成功', $_rdata, $this->auth_key);
            }
            $_rdata['pay_switch'] = 2;
        }
        // sdk预下单
        $_rs = $_pay_class->sdkPreorder($_pay_data);
        if (false == $_rs) {
            return hs_player_responce('1000', '下单失败');
        }
        $_rdata['order_id'] = Session::get('order_id', 'order');
        $_rdata['paytoken'] = md5(uniqid(hs_random(6)));
        Session::set('paytoken', $_rdata['paytoken'], 'order');
        Session::set('order_time', time(), 'order');

        return hs_player_responce('201', '下单成功', $_rdata, $this->auth_key);
    }

    /**
     * 判断是否切换支付
     *
     * @param       $mem_info
     * @param array $_pay_data
     *
     * @return int
     */
    public function isSwitch($mem_info = array(), array $_pay_data) {
        if (empty($mem_info)) {
            $_mem_id = $this->mem_id;
        } else {
            $_mem_id = $mem_info['id'];
        }
        /* 1 获取充值成功次数 首单不切换 */
        $_pay_map['status'] = 2;
        $_pay_map['mem_id'] = $_mem_id;
        $_pay_map['app_id'] = $_pay_data['app_id'];
        $_pay_cnt = \think\Db::name('pay')->where($_pay_map)->count();
        if ($_pay_cnt < 1) {
//            return 0;
        }
        /* 2 IP 过滤 */
        /* 美国IP 不切换 */
        $_ip = $this->request->ip();
        $_ip_arr = \sswsdk\common\Ip::find($_ip);
        if ('中国' != $_ip_arr[0]) {
            return 0;
        }

        /* 3 时间段过滤 */

        return 40001;
    }

    /**
     * 玩家选择支付方式 直接下单
     *
     * @return $this
     */
    public function checkorder() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'user_token',
            'timestamp',
            'device_id',
            'userua',
            'order_id',
            'trans_id',
            'appverifystr',
            'paytoken',
        );
        $_pay_data = $this->getParams($_key_arr);
        $_order_id = $_pay_data['order_id'];
        $_trans_id = $_pay_data['trans_id'];
        //if($_pay_data['is_sandbox']){
        //	 $_payway = 'applepay';
        //}else {
        //	$_payway = 'appletest';
        //}
        //$this->logresult(serialize($_pay_data));
        $_is_sandbox = isset($_pay_data['is_sandbox']) ? $_pay_data['is_sandbox'] : 2;
        $_appverifystr = $_pay_data['appverifystr'];
        $_payway = 'applepay';
        /* 验证paytoken */
//         $_s_paytoken = Session::get('paytoken', 'order');
//         if ( $_pay_token != $_s_paytoken) {
//             return hs_pay_responce(0,"非法请求,参数错误");
//         }
        if (1 == $_is_sandbox) {
            $_is_sandbox == 1;
            $_payway = 'applepay';
        } else {
            $_payway = 'appletest';
            $_is_sandbox == 2;
        }
        /* 1更新支付方式 */
        $_p_class = new  \sswsdk\pay\Pay();
        $_p_class->upPayway($_order_id, $_payway);
        /* 2 验证订单是否通过 */
        $_pay_class = new \sswsdk\pay\Applepay($_is_sandbox);
        $_check_rs = $_pay_class->clientPay($_appverifystr);
        /* 3通知CP */
        if ($_check_rs) {
            /* 验证订单合法性 是否与产品对应上 */
            $_amount = $_pay_class->getProductprice($_check_rs['product_id'], $_order_id);
            if (false == $_amount || 0.01 > $_amount) {
                return hs_player_responce('434', '验单失败');
            }
            $_p_class->sdkNotify($_order_id, $_amount, $_trans_id);
        }
        /* 4 返回信息给客户端 */
        $_rdata = $_p_class->queryOrder($_order_id);
        if (false == $_rdata) {
            return hs_player_responce('434', '验单失败');
        }

        return hs_player_responce('200', '查询成功', $_rdata, $this->auth_key);
    }
}