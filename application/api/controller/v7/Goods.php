<?php
/**
 * Goods.php UTF-8
 * 获取商品列表
 *
 * @date    : 2017/1/22 14:46
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;

class Goods extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 获取商品列表(goods/list)
     * http://doc.1tsdk.com/43?page_id=678
     */
    public function index() {
        $_page = $this->request->param('page/d', 0); /* 第几页 */
        $_offset = $this->request->param('offset/d', $this->row); /* 选取数量 */
        $_map['is_real'] = $this->request->param('is_real/d', 0); /* 1 表示虚拟物品 2 表示实物 */
        $_goods_class = new \sswsdk\goods\Goods();
        $_rdata = $_goods_class->getList($_map, $_page, $_offset);
        if (empty($_rdata['count'])) {
            return hs_api_responce('200', '无商品');
        }

        return hs_api_responce('200', '请求成功', $_rdata);
    }

    /**
     * 获取商品详情(goods/detail)
     * http://doc.1tsdk.com/43?page_id=679
     */
    public function read() {
        $_goods_id = $this->request->param('goodsid/d', 0); /* 礼包ID */
        $_goods_class = new \sswsdk\goods\Goods();
        $_rdata = $_goods_class->getDetail($_goods_id);
        if (empty($_rdata)) {
            return hs_api_responce('200', '无商品');
        }

        return hs_api_responce('200', '请求成功', $_rdata);
    }
}