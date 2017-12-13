<?php
/**
 * Usergoods.php UTF-8
 * 玩家商品
 *
 * @date    : 2017/1/22 16:28
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\player\controller\v7;

use app\common\controller\Baseplayer;
use sswsdk\goods\Goods;
use think\Session;

class Usergoods extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 【内】我的商品(user/goods/list)
     * http://doc.1tsdk.com/43?page_id=681
     *
     * @return $this
     */
    public function index() {
        $_page = get_val($this->rq_data, 'page', 0);
        $_offset = get_val($this->rq_data, 'offset', $this->row);
        $_is_real = get_val($this->rq_data, 'is_real', 2);
        $_flag = 3;
        if (2 == $_is_real) {
            $_flag = 4;
        }
        $_mem_id = Session::get('id', 'user');
        $_goods_class = new Goods();
        $_rdata = $_goods_class->getMemlist($_mem_id, $_page, $_offset, $_flag);

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】兑换商品(user/goods/add)
     * http://doc.1tsdk.com/43?page_id=680
     *
     * @return $this
     */
    public function save() {
        $_goods_id = get_val($this->rq_data, 'goodsid', 0);
        $_goods_class = new Goods();
        $_rdata = $_goods_class->memGetgoods($_goods_id);
        if (empty($_rdata)) {
            return hs_sswsdk_responce('400', '兑换失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }
}