<?php
/**
 * Gift.php UTF-8
 * 礼包接口
 *
 * @date    : 2016年12月9日下午11:42:46
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午11:42:46
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;

class Gift extends Baseapi {
    private $gf_class;
    function _initialize() {
        parent::_initialize();
        $this->gf_class = new \sswsdk\gift\Gift();
    }

    /* 
     * 礼包列表(gift/list)
     * http://doc.1tsdk.com/43?page_id=633
     */
    public function index() {
        $_page = $this->request->param('page/d', 0); /* 第几页 */
        $_offset = $this->request->param('offset/d', $this->row); /* 选取数量 */
        $_map['from'] = $this->request->param('from/d', 0);
        $_map['app_id'] = $this->request->param('gameid/d', 0); /* 来源信息 1-WEB、2-WAP、3-Android、4-IOS、5-WP */
        $_map['is_hot'] = $this->request->param('hot/d', 0);/* 是否热门 2 热门 1 普通 0 所有 */
        $_map['isnew'] = $this->request->param('isnew/d', 0);/* 是否最新 2 新游 1 普通 0 所有 */
        $_map['is_rmd'] = $this->request->param('remd/d', 0);/* 是否推荐 2 推荐 1 普通 0 所有 */
        $_map['is_luxury'] = $this->request->param('luxury/d', 0);/* 是否豪华 2 豪华礼包 1 普通 0 所有 */
        $_rdata = $this->gf_class->getList($_map);
        if (empty($_rdata['count'])) {
            return hs_api_responce('200', '无礼包');
        }

        return hs_api_responce('200', '请求成功', $_rdata);
    }

    /* 
     * 礼包详细信息(gift/detail)
     * http://doc.1tsdk.com/43?page_id=634
     */
    public function read() {
        $_gift_id = $this->request->param('giftid/d', 0); /* 礼包ID */
        $_rdata = $this->gf_class->getDetail($_gift_id);
        if (empty($_rdata)) {
            return hs_api_responce('200', '无礼包');
        }

        return hs_api_responce('200', '请求成功', $_rdata);
    }
}