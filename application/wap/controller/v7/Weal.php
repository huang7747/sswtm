<?php
/**
 * Weal.php UTF-8
 * 卡卷说明
 *
 * @date    : 2017/1/22 19:26
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\wap\controller\v7;

use app\common\controller\Base;
use sswsdk\slide\Slide;

class Weal extends Base {
    function _initialize() {
        parent::_initialize();
    }

    /**
     *
     * 获取卡卷说明(weal/note)
     * http://doc.1tsdk.com/43?page_id=646
     *
     * @return $this
     */
    public function note() {
        $_type = 'carddesc';
        $_slide_class = new Slide($_type);
        $_rdata = $_slide_class->getList();
        if (empty($_rdata['carddesc'])) {
            return hs_api_responce(400, '请求无数据');
        }

        return hs_api_responce(200, '请求成功', $_rdata['carddesc']);
    }
}