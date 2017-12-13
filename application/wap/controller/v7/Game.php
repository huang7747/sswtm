<?php
/**
 * Game.php UTF-8
 * 游戏接口
 *
 * @date    : 2016年12月3日下午3:10:58
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月3日下午3:10:58
 */
namespace app\wap\controller\v7;

use app\common\controller\Baseapi;

class Game extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 游戏详情页面信息(game/wap/detail)
     * http://doc.1tsdk.com/43?page_id=623
     */
    public function read() {
        $_game_id = $this->request->param('gameid/d', 0); /* 游戏ID */
        $_agent_id = $this->request->param('agentid/d', 0); /* 推广渠道ID */
    }
}