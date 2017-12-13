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

class Userconsume extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 【内】消费列表( user/consume/cslist )
     * http://doc.1tsdk.com/43?page_id=1104
     */
    public function csindex() {

        $_page = get_val($this->rq_data, 'page', 1);
        $_offset = get_val($this->rq_data, 'offset', 10);
        $_mem_id = Session::get('id', 'user');

        $_p_class = new \sswsdk\wallet\Payrecord($_mem_id);

        $_rdata = $_p_class->getConsumelist($_mem_id, $_page, $_offset);
        return hs_sswsdk_responce(200, '请求成功', $_rdata);
    }
}