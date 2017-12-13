<?php
/**
 * Coupon.php UTF-8
 * 代金卷接口类
 *
 * @date    : 2017/1/19 17:33
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use sswsdk\integral\Memitg;
use think\Session;

class Coupon extends Baseapi {
    private $coupon_class;

    function _initialize() {
        parent::_initialize();
        $this->coupon_class = new \sswsdk\coupon\Coupon();
    }

    /**
     * 代金卷列表 ( coupon/list )
     * http://doc.1tsdk.com/43?page_id=674
     *
     * @return $this
     */
    public function index() {
        $_page = $this->request->param('page/d', 0); /* 第几页 */
        $_offset = $this->request->param('offset/d', $this->row); /* 选取数量 */
        $_map['app_id'] = $this->request->param('gameid/d', 0); /* 对应游戏 */
        $_map['limittime'] = $this->request->param('limittime/d', 0);/* 是否限制时间 1 限时代金卷 */
        $_map['isrcmd'] = $this->request->param('isrcmd/d', 0);/* 是否推荐 2 推荐 */
        $_rdata = $this->coupon_class->getList($_map, $_page, $_offset);

        return hs_api_responce('200', '请求成功', $_rdata);
    }

    /**
     * 代金卷详情 ( coupon/detail )
     * http://doc.1tsdk.com/43?page_id=675
     *
     * @return $this
     */
    public function read() {
        $_c_id = $this->request->param('couponid/d', 0); /* 礼包ID */
        $_rdata = $this->coupon_class->getDetail($_c_id);
        if (empty($_rdata)) {
            return hs_api_responce('400', '无此代金卷');
        }
        $_mem_id = Session::get('id', 'user');
        $_itg_class = new Memitg();
        $_rdata['myintegral'] = $_itg_class->getMem($_mem_id);

        return hs_api_responce('200', '请求成功', $_rdata);
    }
}