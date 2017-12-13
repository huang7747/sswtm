<?php
/**
 * Baseapi.php UTF-8
 * API接口基类，通用开放
 *
 * @date    : 2016年12月3日下午3:53:40
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月3日下午3:53:40
 */
namespace app\common\controller;

use sswsdk\common\HuoSession;
use think\Controller;

class Baseapi extends Controller {
    protected $agent;
    protected $client_id;
    protected $app_id;
    protected $from;
    protected $mem_id;
    protected $format;
    protected $client_key;
    protected $agent_id;
    protected $row;

    protected function _initialize() {
        parent::_initialize();
        $this->verifyParam();
        $this->row = 10;
    }

    private function verifyParam() {
        $this->verifyAppid();
        $this->verifyAgent();
        $this->verifyClientid();
        $this->verifyFrom();
        $this->verifyFormat();
        $this->verifyUsertoken();
//        $this->verifyTime();
//        $this->verifySign();
    }

    /* 校验agentgame */
    private function verifyAgent() {
        $this->agent = $this->request->param('agentgame/s', 'default');
        $_ag_class = new \sswsdk\agent\Agent(0, $this->agent);
        $this->agent_id = $_ag_class->getAgentid();
    }

    /* 校验appid */
    private function verifyAppid() {
        $this->app_id = $this->request->param('app_id/d', 0);
        if (empty($this->app_id)) {
            return hs_api_responce('403', 'APPID错误');
        }

        return true;
    }

    /* 校验usertoken */
    private function verifyUsertoken() {
        $user_token = $this->request->param('user_token/s', '');
        if (!empty($user_token)) {
            $_se_class = new HuoSession();
            $_se_class->initSession($user_token);
        }
    }

    /* 校验clintid */
    private function verifyClientid() {
        $this->client_id = $this->request->param('client_id/d', 0);
        if (empty($this->client_id)) {
            return hs_api_responce('404', '客户端ID填写错误');
        }
        $_g_class = new \sswsdk\game\Game($this->app_id, $this->client_id);
        $this->client_key = $_g_class->getClientkey();
        if (empty($this->client_key)) {
            return hs_api_responce('404', '客户端ID填写错误');
        }
    }

    /* 校验from */
    private function verifyFrom() {
        $this->from = $this->request->param('from/d', 0);
        if (empty($this->from) || $this->from < 1 || $this->from > 5) {
            return hs_api_responce('405', '请求来源填写错误');
        }
    }

    private function verifyFormat() {
        $this->format = $this->request->param('format/d', 'json');
    }

    private function verifyTime() {
        $_controller = $this->request->controller();
        $_needle = strpos($_controller, '.');
        if ($_needle > 0) {
            $_controller = strtolower(substr($_controller, $_needle + 1));
        }
        if ('System' != $_controller) {
            $time = time();
            $_timestamp = $this->request->param('timestamp/d', '0');
            $time_diff = abs($time - $_timestamp);
            if ($time_diff > 60) {
                return hs_api_responce('407', '请求超时');
            }
        }
    }

    /*
     * 签名校验  md5(api+time+clientkey)
     */
    private function verifySign() {
        $_sign = $this->request->param('sign/s', '');
        if (empty($_sign)) {
            return hs_api_responce('430', '签名为空');
        }
        $_api = $this->request->pathinfo();
        $_timestamp = $this->request->param('timestamp/d', '0');
        $_key = $this->client_key;
        $_veri_sign = md5($_api.$_timestamp.$_key);
        if ($_sign != $_veri_sign) {
            return hs_api_responce('430', '签名校验不通过');
        }
    }
}