<?php
/**
 * Picture.php UTF-8
 * 图片上传 处理
 *
 * @date    : 2017/2/7 16:24
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use sswsdk\player\Meminfo;
use think\Session;

class Picture extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 玩家上传头像(user/portrait/update)
     * http://doc.1tsdk.com/43?page_id=703
     *
     */
    public function setPortrait() {
        $_mem_id = Session::get('id', 'user');
        if (empty($_mem_id)) {
            return hs_api_responce('400', '玩家未登录或登录过期');
        }
        $_portrait = $this->request->file('portrait');
        if (true !== $this->validate(['image' => $_portrait], ['image' => 'require|image'])) {
            return hs_api_responce('400', '请选择正确的头像');
        }
        $_m_class = new Meminfo($_mem_id);
        $_path = $_m_class->setPortrait($_portrait);
        if (false == $_path) {
            return hs_api_responce('400', '头像上传失败');
        }
        $_rdata['portrait'] = $_path;

        return hs_api_responce('200', '修改头像成功', $_rdata);
    }
}