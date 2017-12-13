<?php
/**
 * Gamemoney.php UTF-8
 * 游戏内钱币接口
 *
 * @date    : 2016年12月10日上午12:11:12
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月10日上午12:11:12
 */
namespace app\pay\controller;

use app\common\controller\Basepay;
use think\Session;

class Gamemoney extends Basepay {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 【内】打开WEB-玩家游戏币充值(user/gamemoney/webadd)
     * http://doc.1tsdk.com/12?page_id=435
     */
    function charge() {
        $_se_id = session_id();
        $site = Config::get('domain.SDKSITE');
        $site = $site.'/float.php/Mobile/Wallet/charge'.'?session_id='.$_se_id;
        $this->redirect($site);
//        $this->fetch();
    }

    /*
     * 【内】打开WEB-玩家游戏币充值记录(user/gamemoney/webadd_list)
     * http://doc.1tsdk.com/12?page_id=436
     */
    function chargeindex() {
        $this->fetch();
    }

    /*
     * 【内】打开WEB-玩家游戏币消费记录（user/gamemoney/webpay_list）
     * http://doc.1tsdk.com/12?page_id=437
     */
    function payindex() {
        $this->fetch();
    }
}