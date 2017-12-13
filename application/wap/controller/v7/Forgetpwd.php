<?php
/**
 * Forgetpwd.php UTF-8
 * 忘记密码处理
 *
 * @date    : 2016年11月10日下午2:21:52
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月10日下午2:21:52
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class Forgetpwd extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 【内】打开WEB-找回密码(web/forgetpwd/index)
     * http://doc.1tsdk.com/12?page_id=135
     */
    function index() {
        $site = Config::get('domain.SDKSITE');
        $site = $site.'/float.php/Mobile/Forgetpwd/index';
        $this->redirect($site, 302);
        echo 'Forgetpwd';
//         return hs_player_responce(201, '上传成功');
    }
}