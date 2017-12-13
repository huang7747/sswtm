<?php
/**
 * Search.php UTF-8
 * 搜索接口
 *
 * @date    : 2016年12月9日下午11:23:38
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午11:23:38
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use sswsdk\game\Game;

class Search extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 搜索(search/index)
     * http://doc.1tsdk.com/43?page_id=626
     */
    public function index() {
        $_map['name'] = $this->request->param('q/s', '');/* 搜索关键字 */
        $_type = $this->request->param('searchtype/s', 'game');/* 搜索关键字 */
        $_map['page'] = $this->request->param('page/d', 1); /* 页码 默认为1 代表第一页 */
        $_map['offset'] = $this->request->param('offset/d', $this->row);
        $_map['from'] = $this->request->param('from'); /* 来源信息 1-WEB、2-WAP、3-Android、4-IOS、5-WP */

        if ('game' == $_type) {
            $_g_class = new Game();
            $_rdata = $_g_class->getGameList($_map);

            return hs_api_responce('200', '请求成功', $_rdata);
        }

        return hs_api_responce('400', '无数据');
    }
}