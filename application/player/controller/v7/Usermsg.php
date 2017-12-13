<?php
/**
 * Usermsg.php UTF-8
 * 玩家消息处理
 *
 * @date    : 2017/2/8 10:35
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\player\controller\v7;

use app\common\controller\Baseplayer;
use sswsdk\player\Memmsg;

class Usermsg extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * http://doc.1tsdk.com/43?page_id=685
     * 【内】我的消息(user/msg/list)
     */
    public function index() {
        $_page = get_val($this->rq_data, 'page', 0);
        $_offset = get_val($this->rq_data, 'offset', $this->row);
        $_msg_class = new Memmsg($this->mem_id);
        $_rdata = $_msg_class->getList($_page, $_offset);
        if (empty($_rdata['count'])) {
            return hs_sswsdk_responce('200', '无消息');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * http://doc.1tsdk.com/43?page_id=867
     * 【内】消息删除 (user/msg/delete)
     */
    public function delete() {
        $_msg_id = get_val($this->rq_data, 'msgid', 0);
        if (empty($_msg_id)) {
            return hs_sswsdk_responce('400', '未找到消息');
        }
        $_msg_class = new Memmsg($this->mem_id);
        $_rs = $_msg_class->delete($_msg_id);
        if (false == $_rs) {
            return hs_sswsdk_responce('400', '删除失败');
        }
        return hs_sswsdk_responce('200', '删除成功');
    }
}