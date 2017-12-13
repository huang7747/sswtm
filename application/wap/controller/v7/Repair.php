<?php
/**
 * Repair.php UTF-8
 * 修复工具
 *
 * @date    : 2017/3/16 14:30
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\wap\controller\v7;

use think\Config;
use think\Controller;

class Repair extends Controller {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 打开WEB-闪退修复工具 (system/repair)
     * http://doc.1tsdk.com/43?page_id=1508
     */
    public function geturl() {
        $_site = Config::get('domain.DOWNSITE');
        $_site = $_site.'/ios.mobileconfig';
        $this->redirect($_site);
    }
}