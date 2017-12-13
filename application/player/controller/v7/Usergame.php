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
use think\Config;

class Usergame extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /* 【内】收藏游戏列表(user/game/like_list)
     * http://doc.1tsdk.com/12?page_id=456
     *  */
    protected function likeindex() {
    }

    /* 【内】玩过游戏列表(user/game/play_list) 
     * http://doc.1tsdk.com/12?page_id=457
     * */
    protected function playindex() {
    }

    /* 【内】游戏币种数量（user/game/gm_cnt） 
     * http://doc.1tsdk.com/12?page_id=458
     * */
    protected function gmcnt() {
    }

    /**
     * 【内】游戏币列表( user/game/gmlist )
     * http://doc.1tsdk.com/43?page_id=656
     */
    public function gmindex() {
        $_app_id = get_val($this->rq_data, 'gameid', 0);
        $_page = get_val($this->rq_data, 'page', 0);
        $_offset = get_val($this->rq_data, 'offset', 10);
        $_mem_id = Session::get('id', 'user');
        $_wallet = Config::get('config.wallet');
        $_rate = $_wallet['rate'];

        $_gm_class = new \sswsdk\wallet\Gm($_rate);
        $_rdata = $_gm_class->getMemlist($_mem_id, $_app_id, $_page, $_offset);
        return hs_sswsdk_responce(200, '请求成功', $_rdata, $this->auth_key);
    }
}