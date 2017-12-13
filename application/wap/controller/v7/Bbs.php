<?php
/**
 * Bbs.php UTF-8
 * 进入BBS界面
 *
 * @date    : 2016年11月10日下午2:21:10
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月10日下午2:21:10
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class Bbs extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * Bbs 页面
     */
    function index() {
        $site = Config::get('domain.SDKSITE');
        $site = $site.'/float.php/Mobile/Bbs/index';
        $this->redirect($site, 302);
    }
}