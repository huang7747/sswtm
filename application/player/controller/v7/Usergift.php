<?php
/**
 * Usergift.php UTF-8
 * 玩家礼包接口
 *
 * @date    : 2016年12月9日下午11:42:46
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午11:42:46
 */
namespace app\player\controller\v7;

use app\common\controller\Basehuo;
use sswsdk\gift\Gift;

class Usergift extends Basehuo {
    private $gf_class;

    /**
     * 初始化
     */
    function _initialize() {
        parent::_initialize();
        $this->isUserLogin();
        $this->gf_class = new Gift();
    }

    /* 
     * 【内】我的礼包(user/gift/list)
     * http://doc.1tsdk.com/43?page_id=636
     */
    public function index() {
        $_page = get_val($this->rq_data,'page',0);
        $_offset = get_val($this->rq_data,'offset',0);
        $_map['app_id'] = get_val($this->rq_data,'gameid',0); /* 游戏ID */
        $_rdata = $this->gf_class->getList($_map, $_page, $_offset,2);
        if (empty($_rdata['count'])) {
            return hs_sswsdk_responce('200', '无礼包');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata,$this->auth_key);
    }

    /* 
     * 【内】领取礼包(user/gift/add)
     * http://doc.1tsdk.com/43?page_id=635
     */
    public function save() {
        $_gift_id = get_val($this->rq_data,'giftid',0);
        $_rdata = $this->gf_class->setGift($_gift_id);
        if (empty($_rdata)) {
            return hs_sswsdk_responce('400', '领取失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata,$this->auth_key);
    }
}