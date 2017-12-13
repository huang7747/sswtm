<?php
/**
 * Share.php UTF-8
 * 分享接口
 *
 * @date    : 2016年12月9日下午11:33:08
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午11:33:08
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use think\Session;

class Share extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /* 
     * 游戏分享信息(share/detail)
     * http://doc.1tsdk.com/43?page_id=670
     */
    public function read() {
        $_s_class = new \sswsdk\share\Share();
        $_mem_id = Session::get('id', 'user');
        $_app_id = $this->request->param('gameid/d', 0);
        $_rdata = $_s_class->getGame($_mem_id, $_app_id);

        return hs_api_responce(200,'请求成功',$_rdata);
    }
}