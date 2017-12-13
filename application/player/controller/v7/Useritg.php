<?php
/**
 * Useritg.php UTF-8
 * 玩家积分
 *
 * @date    : 2017/1/21 14:57
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\player\controller\v7;

use app\common\controller\Baseplayer;
use sswsdk\integral\Integral;
use sswsdk\integral\Memitg;

class Useritg extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 【内】获取积分活动(integral/actlist)
     * http://doc.1tsdk.com/43?page_id=683
     *
     */
    public function actindex() {
        $_itg_class = new Integral($this->mem_id);
        $_rdata = $_itg_class->getActList();
        if (empty($_rdata)) {
            return hs_api_responce('400', '请求失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】积分兑换(integral/exchange)
     *
     */
    public function exchange() {
    }

    /**
     * 【内】我的积分邀请奖励列表(user/integral/getinvlist)
     * http://doc.1tsdk.com/43?page_id=692
     *
     */
    public function invite() {
        $_itg_class = new Memitg($this->mem_id);
        $_page = get_val($this->rq_data, 'page', 0);
        $_offset = get_val($this->rq_data, 'offset', $this->row);
        $_rdata = $_itg_class->inviteList($_page, $_offset);
        if (empty($_rdata)) {
            return hs_api_responce('400', '请求失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】获取积分(user/integral/get)
     * http://doc.1tsdk.com/43?page_id=800
     *
     */
    public function getItg() {
        $_mitg_class = new Memitg($this->mem_id);
        $_rdata['myintegral'] = $_mitg_class->get();
        if (empty($_rdata['myintegral'])) {
            return hs_api_responce('400', '请求失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】推广员任务(tguser/detail)
     * http://doc.1tsdk.com/43?page_id=804
     *
     * @return $this
     */
    public function tguser() {
        $_mitg_class = new Memitg($this->mem_id);
        $_rdata = $_mitg_class->getTgAction();
        if (empty($_rdata)) {
            return hs_api_responce('400', '请求失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }
}