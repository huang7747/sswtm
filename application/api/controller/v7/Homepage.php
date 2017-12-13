<?php
/**
 * Homepage.php UTF-8
 * app首页
 *
 * @date    : 2017/1/21 19:33
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use sswsdk\game\Gamelist;
use sswsdk\slide\Slide;

class Homepage extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * @return $this
     */
    public function index() {
        /* 获取广告图 广告文字 */
        $_slide_tyle = 'app_slide';
        $_slide_class = new Slide($_slide_tyle);
        $hometopper = $_slide_class->getList();
        $_from = $this->request->param('from',3);
        //首页广告图
        $_rdata['hometopper'] = $hometopper['app_slide'];
        if($_from == 3){//安卓
            //新游广告图
            $_homenewgame = $_slide_class->getList('app_newslide');
            $_rdata['homenewgame'] = $_homenewgame['app_newslide'];
            //热门广告图
            $_homehotgame = $_slide_class->getList('app_hotslide');
            $_rdata['homehotgame'] = $_homehotgame['app_hotslide'];
            //公益广告图
            $_homehotgame = $_slide_class->getList('app_welfareslide');
            $_rdata['welfareslide'] = $_homehotgame['app_welfareslide'];
        }else{//ios
            //新游广告图
            $_homenewgame = $_slide_class->getList('ios_app_newslide');
            $_rdata['homenewgame'] = $_homenewgame['ios_app_newslide'];
            //热门广告图
            $_homehotgame = $_slide_class->getList('ios_app_hotslide');
            $_rdata['homehotgame'] = $_homehotgame['ios_app_hotslide'];
            //公益广告图
            $_homehotgame = $_slide_class->getList('ios_app_welfareslide');
            $_rdata['welfareslide'] = $_homehotgame['ios_app_welfareslide'];
        }

        $_texthome = $_slide_class->getList('texthome');
        $_rdata['texthome'] = $_texthome['texthome'];
//        if (!empty($_texthome)) {
//            $_rdata = array_merge($_rdata, $_texthome);
//        }
        /* 来源信息 1-WEB、2-WAP、3-Android、4-IOS、5-WP */
        $_page = 1;
        $_offset = 5;
        $_from = $this->request->param('from');
        if (3 == $_from) {
            $_map['g.classify'] = [
                ['=', 3],
                ['between', '300,399'],
                'or'
            ];
            $_offset = 1000;
        } elseif (4 == $_from) {
            $_map['g.classify'] = 4;
        }
        $_map['g.is_app'] = 2; /* app中上线的游戏 */
        $_map['g.is_delete'] = 2; /* 伪删除游戏不显示 */


        $_gl_class = new Gamelist();
        /* 新游首发 */
        $_new_map = $_map;
        $_new_map['g.is_new'] = 2;
        $_rdata['newrmd'] = $_gl_class->gameList($_new_map, $_page, $_offset);
        /* 新游推荐 */
        $_rdata['newgame'] = $_rdata['newrmd'];
        //20170520 取消推荐环节
//        $_newrmd_map = $_map;
//        $_newrmd_map['g.is_new'] = 2;
//        $_rdata['newgame'] = $_gl_class->remdList($_newrmd_map, $_page, $_offset);
        /* 公益服 */
        $_welfare_map = $_map;
        $_welfare_map['g.is_welfare'] = 2;
        $_rdata['welfaregame'] = $_gl_class->gameList($_welfare_map, $_page, $_offset);
        /* 测试表 */
        $_test_map = $_map;
        unset($_test_map['g.is_app']);
        $_rdata['testgame'] = $_gl_class->testList($_test_map, $_page, $_offset);
        /* 新服表 */
        $_rdata['newserver'] = $_gl_class->serverList($_map, $_page, $_offset);
        /* 手游风向标 */
        $_hot_map = $_map;
        $_hot_map['is_hot'] = 2;
        $_rdata['hotgame'] = $_gl_class->gameList($_hot_map, $_page, $_offset);
        /*猜你喜欢 */
        $_offset = 20;
        $_rdata['likegame'] = $_gl_class->gameList($_map, $_page, $_offset, 1);

        return hs_api_responce(200, '请求成功', $_rdata);
    }
}