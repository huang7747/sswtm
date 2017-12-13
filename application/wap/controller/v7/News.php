<?php
/**
 * News.php UTF-8
 * wap 资讯
 *
 * @date    : 2017/6/22 15:46
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : HUOOA 1.0
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class News extends Basewap {
    function _initialize() {
    }

    /**
     * 打开WEB-资讯详情页 (news/webdetail/[newsid])
     * http://doc.1tsdk.com/43?page_id=870
     *
     * @param $id
     */
    function webRead($id = 0) {
        $_site = Config::get('domain.SDKSITE');
        $_site = $_site.'/float.php/Mobile/News/index/id/'.$id;
        $this->redirect($_site, 302);
    }
}