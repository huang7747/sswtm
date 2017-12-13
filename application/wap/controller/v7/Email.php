<?php
/**
 * Email.php UTF-8
 * wap 页面邮箱
 *
 * @date    : 2017/6/22 15:35
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : HUOOA 1.0
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class Email extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * web 绑定邮箱 (user/email/webadd)
     * http://doc.1tsdk.com/43?page_id=1510
     */
    function index() {
        $_se_id = session_id();
        $_site = Config::get('domain.SDKSITE');
        $_site = $_site.'/float.php/Mobile/Code/index'.'?session_id='.$_se_id;
        $this->redirect($_site, 302);
    }
}