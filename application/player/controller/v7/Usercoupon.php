<?php
/**
 * Usercoupon.php UTF-8
 * 玩家代金卷
 *
 * @date    : 2017/1/21 0:15
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\player\controller\v7;

use app\common\controller\Basehuo;
use think\Session;

class Usercoupon extends Basehuo {
    private $coupon_class;

    function _initialize() {
        parent::_initialize();
        $this->isUserLogin();
        $this->coupon_class = new \sswsdk\coupon\Coupon();
    }

    /**
     * 【内】我的代金卷 ( user/coupon/list )
     * http://doc.1tsdk.com/43?page_id=677
     *
     * @return $this
     */
    public function index() {
        $_page = get_val($this->rq_data, 'page', 0);
        $_offset = get_val($this->rq_data, 'offset', $this->row);
        $_mem_id = Session::get('id', 'user');

        $_rdata = $this->coupon_class->getMemlist($_mem_id, $_page, $_offset);

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】兑换代金卷 ( user/coupon/add )
     * http://doc.1tsdk.com/43?page_id=676
     *
     * @return $this
     */
    public function save() {
        $_coupon_id = get_val($this->rq_data, 'couponid', 0);
        $_rdata = $this->coupon_class->memGetcoupon($_coupon_id);
        if (empty($_rdata)){
            return hs_sswsdk_responce('400', '兑换失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }
}