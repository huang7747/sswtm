<?php
/**
 * Gameserver.php UTF-8
 * 游戏开服接口
 *
 * @date    : 2016年12月9日下午11:11:34
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午11:11:34
 */
namespace app\api\controller\v7;

use think\Config;
use app\common\controller\Baseapi;

class Gameserver extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /* 
     * 获取开服列表 (server/list) 
     * http://doc.1tsdk.com/12?page_id=443
     */
    public function index() {
    }

    /* 
     * 获取游戏开服列表(game/server/list)
     * http://doc.1tsdk.com/12?page_id=451
     */
    public function gameindex() {
    }
}