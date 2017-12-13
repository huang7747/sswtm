<?php
/**
 * Usersign.php UTF-8
 * 玩家签到
 *
 * @date    : 2017/2/6 15:39
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\player\controller\v7;

use app\common\controller\Baseplayer;
use sswsdk\player\Memsign;

class Usersign extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 【内】签到列表(user/sign/list)
     * http://doc.1tsdk.com/43?page_id=686
     *
     */
    public function index() {
        $_ms_class = new Memsign($this->mem_id);
        $_rdata = $_ms_class->getList();
        if (empty($_rdata)) {
            return hs_sswsdk_responce('400', '请求失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】玩家签到(user/sign/add)
     * http://doc.1tsdk.com/43?page_id=687
     *
     */
    public function save() {
        $_sign_days = get_val($this->rq_data, 'signday', 0);

        $_ms_class = new Memsign($this->mem_id);
        $_itg = $_ms_class->save($_sign_days);
        if (false === $_itg) {
            return hs_sswsdk_responce('400', '签到失败');
        }

        $_rdata['myintegral'] = $_itg;
        //-1 表示今日已签到
        if (-1 === $_itg) {
            $_rdata['status'] = 3;
            return hs_sswsdk_responce('200', '今日已签到');
        }

        $_rdata['status'] = 2;
        return hs_sswsdk_responce('200', '签到成功', $_rdata, $this->auth_key);
    }
}