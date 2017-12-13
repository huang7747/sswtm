<?php
/**
 * Game.php UTF-8
 * 游戏接口
 *
 * @date    : 2016年12月3日下午3:10:58
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月3日下午3:10:58
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;

class Game extends Baseapi {
    private $g_class;

    function _initialize() {
        parent::_initialize();
        $this->g_class = new \sswsdk\game\Game();
    }

    /*
     * 游戏列表(game/list)
     * URL http://doc.1tsdk.com/43?page_id=622
     * 开服 server
     * 开测 test
     * 推荐 remd
     * 新游 isnew
     * 热门 hot
     * 猜你喜欢 rand
     * 网游单机 category
     * 游戏类型  type
     */
    public function index() {
        $_map['from'] = $this->request->param('from'); /* 来源信息 1-WEB、2-WAP、3-Android、4-IOS、5-WP */
        $_map['page'] = $this->request->param('page/d', 1); /* 页码 默认为1 代表第一页 */
        $_map['offset'] = $this->request->param('offset/d', $this->row);
        $_map['category'] = $this->request->param('category/d', 0); /* 类别 1 单机 2 网游 */
        $_map['type'] = $this->request->param('type/d', 0); /* 	游戏类型,可以逗号隔开 类型id */
        $_map['rand'] = $this->request->param('rand/d', 0); /* 是否随机推荐 1 表示随机获取 */
        $_map['cnt'] = $this->request->param('cnt/d', 0); /* 请求数量 默认为3 与rand配合使用 */
        $_map['hot'] = $this->request->param('hot/d', 0); /* 是否热门 2 热门 1 普通 0 所有 */
        $_map['isnew'] = $this->request->param('isnew/d', 0); /* 是否新游 2 新游 1 普通 0 所有 */
        $_map['remd'] = $this->request->param('remd/d', 0); /* 是否推荐 2 推荐 1 普通 0 所有 */
        $_map['server'] = $this->request->param('server/d', 0); /* 是否开服 2 开服游戏 1 普通 0 */
        $_map['test'] = $this->request->param('test/d', 0);/* 是否开测 2 开测游戏 1 普通 0 */
        $_map['rebate'] = $this->request->param('rebate/d', 0);/* 是否返利 2 返利 其他 不返利  */
        $_map['hasgift'] = $this->request->param('hasgift/d', 0);/* 是否有礼包 2 有礼包 其他 无礼包 */
        $_map['iswelfare'] = $this->request->param('iswelfare/d', 0); /* 是否公益 2 公益 1 普通 0 所有 */
        $_map['test_type'] = $this->request->param('test_type/d', 0); /* 开测类型 1今日 2即将 3已测*/
        $_map['server_type'] = $this->request->param('server_type/d', 0);/* 开服类型 1今日 2即将 3已测*/
        if (!empty($_map['isnew']) && 2 == $_map['isnew']) {
            /*  新游首发 新游+推荐算是首发
                新游推荐 新游戏里选择前五款 */
            if (!empty($_map['remd']) && 2 == $_map['remd']) {
                $_map['remd'] = 0;
            }else{
//                $_map['remd'] = 2;
            }
        }
        $_rdata = $this->g_class->getGameList($_map);

        return hs_api_responce('200', '请求成功', $_rdata);
    }

    /*
     * 获取游戏详情信息(game/detail)
     * http://doc.1tsdk.com/43?page_id=623
     */
    public function read() {
        $_game_id = $this->request->param('gameid/d', 0); /* 游戏ID */
        if (empty($_game_id)) {
            return hs_api_responce('200', '游戏ID错误');
        }
        $_from = $this->from;
        $_g_info = $this->g_class->getGamedetail($_game_id, $_from);

        return hs_api_responce('200', '请求成功', $_g_info);
    }

    /* 
     * 游戏下载地址(game/down)
     * http://doc.1tsdk.com/43?page_id=625
     */
    public function down() {
    }
}