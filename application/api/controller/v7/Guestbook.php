<?php
/**
 * Guestbook.php UTF-8
 * 信息反馈
 *
 * @date    : 2016年12月10日上午12:09:24
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月10日上午12:09:24
 */
namespace app\api\controller\v7;

use app\common\controller\Basehuo;
use think\Session;

class Guestbook extends Basehuo {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 【内】信息反馈( guestbook/write )
     * http://doc.1tsdk.com/43?page_id=648
     */
    public function save() {

        $_gb_class = new \sswsdk\guestbook\Guestbook();
        $_rdata = $_gb_class->save($this->rq_data);
        if (200 == $_rdata) {
            return hs_sswsdk_responce(200,'反馈成功');
        }
        return hs_sswsdk_responce(400,'反馈失败');

    }
}