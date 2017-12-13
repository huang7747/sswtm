<?php
/**
 * System.php UTF-8
 * 系统修复
 *
 * @date    : 2016年8月18日下午9:47:10
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : api 2.0
 */
namespace app\player\controller\v7;

use app\common\controller\Basehuo;
use sswsdk\common\Simplesec;
use sswsdk\game\Version;
use sswsdk\game\Notice;
use sswsdk\log\Devicelog;
use sswsdk\log\Gamelog;
use think\Config;
use think\Db;
use think\Session;

class System extends Basehuo {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 初始化接口
     */
    function install() {
        /* 	渠道ID填写错误 */
        if (empty($this->rq_data['channel_id'])) {
            return hs_player_responce(401, '请求参数错误');
        }
        /* 	渠道URL填写错误  */
        if (empty($this->rq_data['channel_url'])) {
            return hs_player_responce(402, '请求参数错误');
        }
        /* APPID填写错误 */
        if (empty($this->rq_data['app_id'])) {
            return hs_player_responce(403, '请求参数错误');
        }
        /* 客户端ID填写错误 */
        if (empty($this->rq_data['client_id'])) {
            return hs_player_responce(404, '请求参数错误');
        }
        /* 请求来源填写错误 */
        if (empty($this->rq_data['from'])) {
            return hs_player_responce(405, '请求参数错误');
        }
        /* 客户端KEY填写错误 */
        if (empty($this->rq_data['client_key'])) {
            return hs_player_responce(406, '请求参数错误');
        }
        /* 客户端请求时间戳填写错误 */
        if (empty($this->rq_data['timestamp'])) {
            return hs_player_responce(407, '请求参数错误');
        }
        /* 客户端DEVICEID填写错误 */
//        if (empty($this->rq_data['device']['device_id'])) {
//            return hs_player_responce(408, '请求参数错误');
//        }
//        /* 	客户端USERUA填写错误 */
//        if (empty($this->rq_data['device']['userua'])) {
//            return hs_player_responce(409, '请求参数错误');
//        }
        /* 客户端本地IP填写错误 */
        //         if (empty($this->rq_data['device']['local_ip'])) {
        //             return hs_player_responce(410, '请求参数错误');
        //         }
        $_data['c_id'] = $this->rq_data['channel_id'];
        $_data['channel_url'] = $this->rq_data['channel_url'];
        $_data['app_id'] = $this->rq_data['app_id'];
        $_data['client_id'] = $this->rq_data['client_id'];
        $_data['from'] = $this->rq_data['from'];
        $_data['client_key'] = $this->rq_data['client_key'];
        $_data['timestamp'] = isset($this->rq_data['timestamp']) ? $this->rq_data['timestamp'] : '';
        $_data['device_id'] = isset($this->rq_data['device']['device_id']) ?$this->rq_data['device']['device_id']:'';
        $_data['userua'] = isset($this->rq_data['device']['userua']) ?$this->rq_data['device']['userua']:'';
        $_data['deviceinfo'] = isset($this->rq_data['device']['deviceinfo']) ?$this->rq_data['device']['deviceinfo']:'';
        $_data['idfv'] = isset($this->rq_data['device']['idfv']) ? $this->rq_data['device']['idfv'] : '';
        $_data['idfa'] = isset($this->rq_data['device']['idfa']) ? $this->rq_data['device']['idfa'] : '';
        $_data['mac'] = isset($this->rq_data['device']['mac']) ? $this->rq_data['device']['mac'] : '';
        $_data['local_ip'] = isset($this->rq_data['device']['local_ip']) ? $this->rq_data['device']['local_ip'] : '';
        $_data['ip'] = $this->request->ip();
        $_data['ipaddrid'] = isset($this->rq_data['device']['ipaddrid']) ? $this->rq_data['device']['ipaddrid'] : '';
        $_data['create_time'] = time();
        Db::name('channel_mem_log')->insert($_data);
        //通过 c_id 查询 rsapub
        $_path = SITE_PATH.'conf/extra/key/'.$_data['c_id'].'/rsa_public_key.pem';
        $_key_content = $this->_readFile($_path);
        $_key_content = str_replace("-----BEGIN PUBLIC KEY-----", "", $_key_content);
        $_key_content = str_replace("-----END PUBLIC KEY-----", "", $_key_content);
        $_key_content = str_replace("\r\n", "", $_key_content);
        $_key_content = str_replace("\n", "", $_key_content);
        $_rdata['rsapub'] = $_key_content;

        return hs_sswsdk_responce(200, '请求成功', $_rdata, $this->auth_key);
    }

    private function _readFile($file) {
        $_ret = false;
        if (!file_exists($file)) {
//            $this->_error("The file {$file} is not exists");
        } else {
            $_ret = file_get_contents($file);
        }

        return $_ret;
    }

    /**
     * 初始化接口
     *
     * @return $this
     */
    function open() {
        $_key_arr = array(
            'app_id', 'client_id', 'from', 'device_id', 'userua', 'open_cnt'
        );
        $_data = $this->getParams($_key_arr);
        $_agentgame = $this->getVal($_data, 'agentgame', '');
        $_gol_data['mem_id'] = 0;
        $_gol_data['ver_id'] = $this->getVal($_data, 'client_id', 0);
        $_gol_data['app_id'] = $this->getVal($_data, 'app_id', 0);
        $_gol_data['agentname'] = $_agentgame;
        $_gol_data['agent_id'] = $this->getAgentid($_gol_data['agentname']);
        $_gol_data['device_id'] = $this->getVal($_data, 'device_id', '');
        $_gol_data['idfa'] = $this->getVal($_data, 'idfa', '');
        $_gol_data['idfv'] = $this->getVal($_data, 'idfv', '');
        $_gol_data['mac'] = $this->getVal($_data, 'mac', '');
        $_gol_data['deviceinfo'] = $this->getVal($_data, 'deviceinfo', '');
        $_gol_data['userua'] = $this->getVal($_data, 'userua', '');
        $_gol_data['local_ip'] = $this->getVal($_data, 'local_ip', '');
        $_gol_data['ipaddrid'] = $this->getVal($_data, 'ipaddrid', 0);
        $_gol_data['create_time'] = time();
        $_gol_data['ip'] = $this->request->ip();
        //插入打开记录
        $_gl_class = new Gamelog('game_openlog');
        $_rs = $_gl_class->insert($_gol_data);
        if (!$_rs) {
            return hs_player_responce('1000', '服务器内部错误');
        }
        /* 查询是否切换支付 */
        $_rdata['check'] = '0';
        $_rdata['agentgame'] = $_agentgame;
        if (empty($_agentgame) && 1 == $_data['open_cnt']) {
            //调起异步操作 计算agent_game wuyonghongtest
            //computeAg();
            /* 获取渠道 */
            $_dl_class = new Devicelog();
            $_rdata['agentgame'] = $_dl_class->getAgentgame($_gol_data['device_id'], $_gol_data['app_id']);
        }
        /* 查询是否有更新 */
        $_gv_class = new Version($_gol_data['device_id']);
        $_gv_info = $_gv_class->getLastinfo();
        $_rdata['up_status'] = 0;
        $_rdata['up_url'] = '';
        if ($_gv_info && $_gv_info['gc_id'] > $_gol_data['ver_id']) {
            $_rdata['up_status'] = 1;
            $_rdata['up_url'] = $_gv_info['packageurl'];
        }
        /* 获得帮助信息 */
        $_help['qq'] = '';
        $_help['qqgroup'] = '';
        $_help['wx'] = '';
        $_help['tel'] = '';
        $_rdata['help'] = $_help;
//        $this->setStartsession();
        $_rdata['ip'] = $this->request->ip();
        $_ss_class = new Simplesec();
        $_rdata['user_token'] = $_ss_class->encode(session_id(), Config::get('config.HSAUTHCODE'));
        $_rdata['timestamp'] = time();
        /* 获得公告信息 */
        $_notice_class = new Notice();
        $_rdata['notice_list'] = $_notice_class->getNoticeList($_gol_data['app_id']);
        Session::set('open_cnt', $this->rq_data['open_cnt'], 'device');

        return hs_player_responce(200, 'INIT OK!', $_rdata, $this->auth_key);
    }


    /**
     * 公告信息(notice)
     *
     * @return $this
     */
    function notice() {
        $_data['title'] = '测试标题';
        $_data['url'] = 'http://www.baidu.com';
        $_data['content'] = '测试内容';

        return hs_player_responce(200, 'test ok', $_data, $this->auth_key);
    }
}