<?php
namespace app\common\controller;

use think\Config;
use think\Cookie;
use think\Request;
use think\Session;

class Basewap extends Base {
    protected $request; /* 请求实例 */
    protected function _initialize() {
        parent::_initialize();
        // 获取请求参数
        $this->request = Request::instance();
        $_key = $this->request->param('key/s', '');
        $_data = $this->request->param('data/s', '');
        if (!empty($_key) && !empty($_data)) {
            // 解析请求数据
            $_pri_path = CONF_PATH.'extra/key/rsa_private_key.pem';
            $_rq_class = new \sswsdk\response\Rsaauth(false, 0, $_pri_path);
            $_auth_key = $_rq_class->getAuthkey($_key);
            $this->auth_key = $_auth_key;
            $this->web_key = $_auth_key;
            if (false == $_auth_key) {
                echo "请求key错误";
                $this->error("请求key错误");
            }
            $_rq_data = $_rq_class->getRqdata($_auth_key, $_data);
            if (false == $_rq_data) {
                $this->error("请求数据非法");
            }
            $this->rq_data = $_rq_data;
            $_rs = $this->se_class->setSession($this->rq_data);
            if(!empty($_rq_data['gameid'])) {
                Session::set('gameid', $_rq_data['gameid'], 'app');
            }
            $_con_arr = explode('.', $this->request->controller());
            $_con_name = strtoupper($_con_arr[1]);
            if (false == $_rs && 'FORGETPWD' != $_con_name) {
                $this->resetToken();
                exit;
            }
            if (!empty($this->web_key)) {
                Session::set('web_key', $this->web_key);
            }
            if ('FORGETPWD' != $_con_name && empty(Session::get('user.id'))) {
                $this->resetToken();
            }
            $this->setFloatid();
        } else {
            $this->isUserwapLogin();
        }
    }

    private function setFloatid() {
        $_mem_id = Session::get('id', 'user');
        $_session_id = session_id();
        $_float_id = $_mem_id."_".$_session_id;
        $_ss_class = new \sswsdk\common\Authcode();
        $_str = $_ss_class->discuzAuthcode($_float_id, 'ENCODE', Config::get('config.COOKIEKEY'));
        Cookie::set('sswsdk_float_id', $_str);
    }
}