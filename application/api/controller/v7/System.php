<?php
/**
 * System.php UTF-8
 * 系统公共操作
 *
 * @date    : 2016年12月3日上午11:00:44
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月3日上午11:00:44
 */
namespace app\api\controller\v7;

use app\common\controller\Basehuo;
use sswsdk\slide\Slide;
use think\Session;

class System extends Basehuo {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 初始化APP 获取app各种信息
     */
    public function init() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'timestamp',
            'device_id',
            'userua',
            'open_cnt',
            'verid'
        );
        $_request_data = $this->getParams($_key_arr);
        /* 插入初始化信息 */
        /* 输出返回信息 */
        $_rdata['ip'] = $this->readClientip();
        $_rdata['user_token'] = $this->readNewtoken();
        $_rdata['agentgame'] = $this->getAgentgame($this->rq_data['agentgame']);
        $_rdata['timestamp'] = time();
        $_rdata['up_info'] = $this->readUpinfo(
            $_request_data['app_id'], $_request_data['client_id'], $_request_data['verid']
        );
        $_rdata['help'] = $this->readHelpinfo();
        $_rdata['newmsg'] = 1;
        $_mem_id = Session::get('id', 'user');
        if (!empty($_mem_id)) {
            $_msg_class = new \sswsdk\player\Memmsg($_mem_id);
            if ($_msg_class->hasNew()) {
                $_rdata['newmsg'] = 2;
            }
        }

//        $_rdata['splash'] = $this->readSplash();
        return hs_player_responce(200, '打开成功', $_rdata, $this->auth_key);
    }

    /*
     * 获取服务器时间
     */
    public function time() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'timestamp',
            'device_id',
            'userua'
        );
        $this->getParams($_key_arr);
        $_rdata['timestamp'] = time();

        return hs_player_responce(200, '获取服务器时间成功', $_rdata);
    }

    /*
     * 客服信息
     */
    public function help() {
//        $_key_arr = array(
//            'app_id',
//            'client_id',
//            'from',
//            'timestamp',
//            'device_id',
//            'userua'
//        );
//        $this->getParams($_key_arr);
        $_rdata = $this->readHelpinfo();
        if (empty($_rdata)) {
            return hs_sswsdk_responce(400, '无客服信息');
        }

        return hs_sswsdk_responce(200, '获取客服信息成功', $_rdata, $this->auth_key);
    }

    /*
     * 是否有新版本
     */
    public function newVersion() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'timestamp',
            'device_id',
            'userua'
        );
        $_request_data = $this->getParams($_key_arr);
        $_rdata['up_info'] = $this->readUpinfo($_request_data['client_id']);
        if (empty($_rdata['up_info'])) {
            return hs_player_responce(400, '无新版本信息');
        }

        return hs_player_responce(200, '获取新版本信息成功', $_rdata);
    }

    /*
     * 获取版本信息
     */
    public function version() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'timestamp',
            'device_id',
            'userua'
        );
        $this->getParams($_key_arr);
        $_rdata['content'] = $this->readVerinfo();
        if (empty($_rdata['content'])) {
            return hs_player_responce(400, '无新版本信息');
        }

        return hs_player_responce(200, '获取版本信息成功', $_rdata);
    }

    /*
     * 获取开机闪屏图
     */
    public function splash() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'timestamp',
            'device_id',
            'userua'
        );
        $this->getParams($_key_arr);
        $_rdata['splash'] = $this->readSplash();
        if (empty($_rdata['splash'])) {
            return hs_player_responce(400, '无闪屏图');
        }

        return hs_player_responce(200, '获取闪屏图成功', $_rdata);
    }

    /* 获取客户端IP */
    private function readClientip() {
        return $this->request->ip();
    }

    private function readNewtoken() {
        $_ss_class = new \sswsdk\common\Simplesec();
        $_user_token = $_ss_class->encode(session_id(), \think\Config::get('config.HSAUTHCODE'));

        return $_user_token;
    }

    /*
     * 获取帮助信息
     * qq
     * qqgroup
     * wx
     * tel
     * servicetime
     */
    private function readHelpinfo() {
        $_g_class = new \sswsdk\game\Game();
        $_hinfo = $_g_class->game_contact();
//        $_hinfo = array();
//        $_hinfo['qq'] = 'qq111';
//        $_hinfo['qqgroup'] = 'qqgroup222';
//        $_hinfo['wx'] = 'cokshw';
//        $_hinfo['tel'] = '156986532569';
//        $_hinfo['servicetime'] = '09:00 - 17:00';
        return $_hinfo;
    }

    /*
     * 获取闪屏图
     * img
     * gameid
     * url
     */
    private function readSplash() {
        $_sinfo = array();
        $_from = !empty($this->rq_data['from']) ? $this->rq_data['from'] : 0;
        if (3 == $_from) {
            $_type = "andapp_splash";
        } else if (4 == $_from) {
            $_type = "iosapp_splash";
        } else {
            return null;
        }
        $_s_class = new Slide($_type);
//        $_rdata = $_s_class->getSplash();
        $_rdata = array();

        return $_rdata;
    }

    /**
     * 获取轮播图
     *
     * @return false|null|\PDOStatement|string|\think\Collection
     */
    private function readSlide() {
        $_sinfo = array();
        $_type = !empty($this->rq_data['type']) ? $this->rq_data['type'] : '';
        if (empty($_type)) {
            return null;
        }
        $_s_class = new Slide($_type);
        $_rdata = $_s_class->getSlide();

        return $_rdata;
    }

    /*
     * 获取更新版本信息
     * @return
     * array(up_status,url,content);
     */
    private function readUpinfo($app_id, $client_id, $verid = 0) {
        $_g_class = new \sswsdk\game\Game($app_id, $client_id);
        $_upinfo = $_g_class->getUpinfo($client_id, $verid);
        if (false == $_upinfo) {
            $_upinfo['up_status'] = 0;
            $_upinfo['url'] = '';
            $_upinfo['content'] = '';

            return $_upinfo;
        } else {
            return $_upinfo;
        }
    }

    /*
     * 获取单下版本信息
     * @param app_id
     * @param client_id
     * return
     * content
     */
    private function readVerinfo() {
        $_content = '本版本信息';

        return $_content;
    }
}