<?php
/**
 * Cp.php UTF-8
 * CP对接接口
 *
 * @date    : 2016年11月18日下午12:10:36
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年11月18日下午12:10:36
 */
namespace app\cp\controller\v7;

use think\Controller;
use think\Session;
use think\Config;

class Cp extends Controller {
    private $mem_id;
    private $app_id;
    private $user_token;
    private $sign;
    private $app_key;

    function _initialize() {
        parent::_initialize();
    }

    private function cpReturn($status = '0', $msg = '请求参数错误') {
        $_rdata = array(
            'status' => $status,
            'msg'    => $msg
        );
        echo json_encode($_rdata);
        exit;
    }

    public function check() {
        /* 1 查询是否具有访问权限 */
        $_rs = $this->checkAuth();
        $_urldata = $this->request->param();
        \think\Log::write($_urldata, 'error');//记录请求数据
        $this->app_id = $this->request->param('app_id');
        $this->mem_id = $this->request->param('mem_id');
        $this->user_token = $this->request->param('user_token');
        $this->sign = $this->request->param('sign');
        /* 0 检查参数 */
        $this->checkParam();
        /* 13 校验user_token */
        $this->getSession();
        /* 15 校验玩家 */
        $this->checkUser();
        /* 11 校验APPID */
        $this->checkAppid();
        /* 12 校验签名 */
        $this->verifySign();
        /* 16 检查访问次数 */
        $this->checkCnt();
        $this->cpReturn('1', '验证成功');
    }

    private function checkAppid() {
        $_se_app_id = Session::get('app_id', 'app');
        if ($_se_app_id != $this->app_id) {
            $this->cpReturn('11', '游戏ID(app_id)错误');
        }
        $_g_class = new \sswsdk\game\Game();
        $_game_info = $_g_class->getGameinfo($this->app_id);
        if (empty($_game_info['app_key'])) {
            $this->cpReturn('11', '游戏ID(app_id)错误');
        }
        $this->app_key = $_game_info['app_key'];
        return true;
    }

    private function checkUser() {
        $_se_mem_id = Session::get('id', 'user');
        if ($_se_mem_id != $this->mem_id) {
            $this->cpReturn('15', '玩家未登陆');
        }
        return true;
    }

    /* 1 校验参数 */
    private function checkParam() {
        if (empty($this->app_id) || $this->app_id < 0) {
            $this->cpReturn('0', '请求参数为空 app_id');
        }
        if (empty($this->mem_id) || $this->mem_id < 0) {
            $this->cpReturn('0', '请求参数为空  mem_id');
        }
        if (empty($this->user_token)) {
            $this->cpReturn('0', '请求参数为空  user_token');
        }
        if (empty($this->sign)) {
            $this->cpReturn('0', '请求参数为空  sign');
        }
    }

    /* 校验权限 */
    private function checkAuth() {
        // $this->cpReturn('100','没有接口访问权限');
        return true;
    }

    /* 检查次数 */
    private function checkCnt() {
        // $this->cpReturn('16','访问太频繁，超过访问次数');
        $_cnt = Session::get('cnt', 'cp');
        if (empty($_cnt)) {
            $_cnt = 0;
            Session::set('cnt', $_cnt, 'cp');
        }
        $_cnt++;
        Session::set('cnt', $_cnt, 'cp');
        return true;
    }

    /*12  校验签名 */
    private function verifySign() {
        $_signstr = "app_id=".$this->app_id."&mem_id=".$this->mem_id."&user_token=".$this->user_token."&app_key="
                    .$this->app_key;
        $_verify_sign = md5($_signstr);
        if ($this->sign != $_verify_sign) {
            $this->cpReturn('12', '签名校验不通过');
        }
        return true;
    }

    private function getSession() {
        $_user_token = $this->user_token;
        if (empty($_user_token)) {
            $this->cpReturn('0', '请求参数为空  user_token');
        }
        $_session_id = \sswsdk\common\Simplesec::decode($_user_token, Config::get('config.CPAUTHCODE'));
        if (empty($_session_id)) {
            $this->cpReturn('13', 'user_token错误');
        }
        $config['id'] = $_session_id;
        Session::init($config);
        if (!Session::get('id', 'user')) {
            $this->cpReturn('13', 'user_token错误');
        }
        return true;
    }
}