<?php
namespace app\wap\controller\v7;

use app\common\controller\Basewap;
use think\Config;

class Wallet extends Basewap {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 游戏币充值
     */
    function index() {
        $_se_id = session_id();
        $site = Config::get('domain.SDKSITE');
        $site = $site.'/float.php/Mobile/Wallet/charge'.'?session_id='.$_se_id;
        $this->redirect($site);
    }

    /*
     * 平台币充值
     */
    function ptb_charge() {
        $_se_id = session_id();
        $site = Config::get('domain.SDKSITE');
        $site = $site.'/float.php/Mobile/Wallet/ptb_charge'.'?session_id='.$_se_id;
        $this->redirect($site);
    }
}