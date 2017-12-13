<?php
/**
 * Order.php UTF-8
 * 订单查询
 *
 * @date    : 2016年11月18日上午10:01:11
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月18日上午10:01:11
 */
namespace app\player\controller\v7;

use app\common\controller\Baseplayer;

class Order extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 查询订单
     */
    public function queryOrder() {
        if (empty($this->rq_data['order_id'])) {
            return hs_player_responce('424', '订单号为空');
        }
        $_order_id = $this->rq_data['order_id'];
        $_pay_class = new \sswsdk\pay\Pay();
        $_rdata = $_pay_class->queryOrder($_order_id);
        if (false == $_rdata) {
            return hs_player_responce('424', '查询失败');
        }
        return hs_player_responce('200', '查询成功', $_rdata, $this->auth_key);
    }
}