<?php
/**
 * Integral.php UTF-8
 * 积分处理公共接口类
 *
 * @date    : 2017/1/22 16:14
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use sswsdk\integral\Memitg as IntegralClass;
use think\Session;

class Integral extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 获取积分排行表(integral/ranklist)
     * http://doc.1tsdk.com/43?page_id=682
     *
     * @return $this
     */
    public function ranklist() {
        $_page = $this->request->param('page/d', 0); /* 第几页 */
        $_offset = $this->request->param('offset/d', $this->row); /* 选取数量 */
        $_mem_id = Session::get('id', 'user');
        $_itg_class = new IntegralClass($_mem_id);
        $_rdata['myintegral'] = $_itg_class->get();
        $_rdata['myrank'] = $_itg_class->getRank();
        $_ranklist = $_itg_class->getRanklist($_page, $_offset);
        if (!empty($_ranklist)) {
            $_rdata = array_merge($_rdata, $_ranklist);
        }

        return hs_api_responce(200, '请求成功', $_rdata);
    }
}