<?php
/**
 * Gamecomment.php UTF-8
 *
 * @date    : 2016年12月9日下午11:11:24
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午11:11:24
 */
namespace app\api\controller\v7;

use think\Config;
use app\common\controller\Base;

class Gamecomment extends Base {
    function _initialize() {
        parent::_initialize();
    }

    /* 
     * 游戏评论列表/子评论列表（game/comment_list）
     * 子评论列表 (game/comment_list)
     * http://doc.1tsdk.com/12?page_id=278
     */
    public function index() {
    }

    /*
     * 【内】添加游戏评论（game/comment_add）
     * http://doc.1tsdk.com/12?page_id=280
     */
    public function save() {
    }
}