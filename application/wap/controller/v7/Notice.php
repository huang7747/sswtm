<?php
/**
 * Notice.php UTF-8
 * 通知接口
 *
 * @date    : 2017/6/22 15:43
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.2
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class Notice extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * web 公告详情页 (norice/webdetail/:id) (notice/webdetail/:id)
     * http://doc.1tsdk.com/43?page_id=1323
     *
     * @param $id
     */
    function index($id) {
        $_site = Config::get('domain.SDKSITE');
        $_site = $_site.'/float.php/Mobile/News/norice/id/'.$id;
        $this->redirect($_site, 302);
    }
}