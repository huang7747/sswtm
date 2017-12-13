<?php
/**
 * Message.php UTF-8
 * 玩家消息处理
 *
 * @date    : 2017/2/8 10:35
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\wap\controller\v7;

use app\common\controller\Baseplayer;
use sswsdk\player\Memmsg;
use think\Session;

class Message extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * http://doc.1tsdk.com/43?page_id=694
     *  【内】消息读取( user/msg/detail )
     */
    public function read() {
        $_mem_id = Session::get('id', 'user');
        if (empty($_mem_id)) {
            return "请登录";
        }
        $_msg_id = get_val($this->rq_data, 'msgid', 0);
        if (empty($_msg_id)) {
            return "未找到消息";
        }
        $_mem_id = Session::get('id', 'user');
        $_msg_class = new Memmsg($_mem_id);
        $_rdata = $_msg_class->read($_msg_id);
        if (empty($_rdata)) {
            return "未找到消息";
        }
        $this->assign('data', $_rdata);

        return $this->fetch('message/index');
    }
}