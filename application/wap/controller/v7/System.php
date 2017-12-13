<?php
/**
 * System.php UTF-8
 * wap 系统网页
 *
 * @date    : 2016年12月10日上午12:22:07
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月10日上午12:22:07
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class System extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 【内】折扣说明(discount/index)
     * http://doc.1tsdk.com/12?page_id=285
     */
    function readDiscount() {
    }

    /*
     * 【内】闪退修复工具（system/repair）
     * http://doc.1tsdk.com/12?page_id=286
     */
    function readTool() {
    }
}