<?php
/**
 * Help.php UTF-8
 * 帮助中心
 *
 * @date    : 2016年11月10日下午2:23:00
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月10日下午2:23:00
 */
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class Help extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 帮助中心
     */
    function index() {
        $site = Config::get('domain.SDKSITE');
        $site = $site.'/float.php/Mobile/Help/index';
        $this->redirect($site, 302);
        // return hs_player_responce(201, '上传成功');
    }
}