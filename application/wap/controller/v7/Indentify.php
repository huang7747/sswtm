<?php
/**
 * Code.php UTF-8
 * 验证码接口
 *
 * @date    : 2017年5月10日下午11:13:21
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : OU <ozf@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class Indentify extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 实名验证
     */
    function index() {
        $_se_id = session_id();
        $site = Config::get('domain.SDKSITE');
        $site = $site.'/float.php/Mobile/Indentify/index'.'?session_id='.$_se_id;
        $this->redirect($site);
    }
}