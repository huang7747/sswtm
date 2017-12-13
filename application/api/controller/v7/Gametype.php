<?php
/**
 * Gametype.php UTF-8
 * 游戏类型类
 *
 * @date    : 2016年12月9日下午10:17:26
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午10:17:26
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;

class Gametype extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /* 
     * 游戏分类(game/gametype)
     * http://doc.1tsdk.com/43?page_id=621
     */
    public function index() {
        $_g_class = new \sswsdk\game\Gametype();

        $_rdata = $_g_class->getTypelist();
        return hs_api_responce('200', '请求成功',$_rdata);
    }
}