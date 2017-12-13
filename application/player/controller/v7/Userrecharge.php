<?php
/**
 * Usergame.php UTF-8
 * 玩家游戏接口
 *
 * @date    : 2016年12月10日上午12:16:18
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月10日上午12:16:18
 */
namespace app\player\controller\v7;

use app\common\controller\Baseplayer;
use think\Session;

class Userrecharge extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 【内】平台币充值列表( user/recharge/rclist )
     * http://doc.1tsdk.com/43?page_id=1105
     */
    public function rcindex() {
        $_page = get_val($this->rq_data, 'page', 1);
        $_offset = get_val($this->rq_data, 'offset', 10);
        $_mem_id = Session::get('id', 'user');
        $_p_class = new \sswsdk\wallet\Payrecord($_mem_id);
        $_rdata = $_p_class->getrechargelist($_mem_id, $_page, $_offset);

        return hs_sswsdk_responce(200, '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】游戏币充值列表( user/gm/rclist )
     * http://doc.1tsdk.com/43?page_id=1281
     */
    public function gm_recharge_list(){
        $_page = get_val($this->rq_data, 'page', 1);
        $_offset = get_val($this->rq_data, 'offset', 10);
        $_mem_id = Session::get('id', 'user');
        $_p_class = new \sswsdk\wallet\Payrecord($_mem_id);
        $_rdata = $_p_class->getgmrechargelist($_mem_id, $_page, $_offset);

        return hs_sswsdk_responce(200, '请求成功', $_rdata, $this->auth_key);
    }
}