<?php
/**
 * Slide.php UTF-8
 *
 * @date    : 2016年12月10日上午12:05:21
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月10日上午12:05:21
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;

class Slide extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /**
     *
     * 轮播图、广告（slide/list）
     * http://doc.1tsdk.com/43?page_id=671
     *
     * @return $this
     */
    public function index() {
        $_type = $this->request->param('type/s', '', 'trim');
        $_slide_class = new \sswsdk\slide\Slide($_type);
        $_rdata = $_slide_class->getList();
        if (empty($_rdata)) {
            return hs_api_responce(400, '请求无数据');
        }

        return hs_api_responce(200, '请求成功', $_rdata);
    }
}