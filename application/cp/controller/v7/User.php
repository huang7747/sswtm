<?php
/**
 * User.php UTF-8
 * 校验用户名
 *
 * @date    : 2017/4/5 15:35
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\cp\controller\v7;

use think\Controller;

class User extends Controller {
    private $username;
    private $timestamp;
    private $sign;
    private $key;

    function _initialize() {
        parent::_initialize();
        $this->key = "VNEJ22Z2P2L69P6F";
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
        $this->username = $this->request->param('username');
        $this->timestamp = $this->request->param('timestamp');
        $this->sign = $this->request->param('sign');
        /* 0 检查参数 */
        $this->checkParam();
        $this->verifySign();
        /* 15 校验玩家 */
        $this->checkUser();
        $this->cpReturn('1', '成功');
    }

    private function checkUser() {
        //用户名必须为数字字母组合, 长度在6-16位之间
        $checkExpressions = "/^[a-zA-Z0-9]+$/i";
        $len = strlen($this->username);
        if ($len<6 || $len>16 || false == preg_match($checkExpressions, $this->username)){
            $this->cpReturn('-1', '用户名不合法');
        }

        $_map['username'] = $this->username;
        $_cnt = \think\Db::name('members')->where($_map)->count();
        if ($_cnt > 0) {
            $this->cpReturn('-3', '	用户名已经存在');
        }
    }

    /* 1 校验参数 */
    private function checkParam() {
        if (empty($this->username) || $this->username < 0) {
            $this->cpReturn('0', '请求参数为空 username');
        }
        if (empty($this->timestamp) || $this->timestamp < 0) {
            $this->cpReturn('0', '请求参数为空  timestamp');
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

    /*12  校验签名 */
    private function verifySign() {
        $_signstr = "key=".$this->key."&timestamp=".$this->timestamp."&username=".$this->username;
        $_verify_sign = md5($_signstr);
        if ($this->sign != $_verify_sign) {
            $this->cpReturn('0', '签名校验不通过');
        }

        return true;
    }
}