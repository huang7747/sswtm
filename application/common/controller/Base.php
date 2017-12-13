<?php
namespace app\common\controller;

use sswsdk\common\HuoSession;
use think\Config;
use think\Controller;
use think\Cookie;
use think\Log;
use think\Request;
use think\Session;

class Base extends Controller {
    protected $rq_data = array(); /* 请求数据数组 */
    protected $web_key; /* WEB请求的KEY */
    protected $auth_key; /* API请求的KEY */
    protected $row;
    protected $se_class;

    protected function _initialize() {
        $_urldata['url'] = $this->request->server('HTTP_HOST').$this->request->server('REQUEST_URI');
        $_urldata['param'] = $this->request->param();
        Log::write($_urldata['url'].'?'.$this->request->getContent(), 'error'); // 记录请求数据
        $this->se_class = new HuoSession();
        $this->row = 10;  /* 列表默认取10个 */
    }

    protected function getAgentid($agentname) {
        $_agent_class = new \sswsdk\agent\Agent(0, $agentname);
        $_agent_id = $_agent_class->getAgentid();
        return $_agent_id;
    }

    /* 验证必传信息 */
    protected function verifyParam(array $data, array $key_arr) {
        $_veri_arr = array(
            'app_id'        => '403',
            'client_id'     => '404',
            'from'          => '405',
            'client_key'    => '406',
            'timestamp'     => '407',
            'device_id'     => '408',
            'userua'        => '409',
            'local_ip'      => '410',
            'username'      => '411',
            'password'      => '412',
            'mobile'        => '413',
            'smstype'       => '414',
            'open_cnt'      => '415',
            'smscode'       => '416',
            'openid'        => '417',
            'access_token'  => '418',
            'role_type'     => '419',
            'server_id'     => '420',
            'server_name'   => '421',
            'role_id'       => '422',
            'role_name'     => '423',
            'cp_order_id'   => '424',
            'product_price' => '425',
            'product_count' => '426',
            'product_id'    => '427',
            'product_name'  => '428',
            'product_desc'  => '429',
            'sign'          => '430',
            'key'           => '431',
            'order_id'      => '432',
            'trans_id'      => '433',
            'appverifystr'  => '434',
            'paytoken'      => '435',
        );
        foreach ($key_arr as $_val) {
            if (empty($data[$_val])) {
                if (!isset($_veri_arr[$_val])) {
                    return hs_player_responce(400, "请求参数".$_val."错误");
                }
                return hs_player_responce($_veri_arr[$_val], '请求参数'.$_val.'错误');
            }
        }
        return true;
    }

    /* 获取参数 */
    protected function getParams(array $key_arr = array()) {
        $_data = $this->rq_data;
        $_rdata = array();
        foreach ($_data as $_k => $_v) {
            if (is_array($_v)) {
                foreach ($_v as $_k1 => $_v2) {
                    $_rdata[$_k1] = $_v2;
                }
            } else {
                $_rdata[$_k] = $_v;
            }
        }
        if (!empty($key_arr)) {
            $this->verifyParam($_rdata, $key_arr);
        }
        return $_rdata;
    }

    protected function getVal($data, $val, $defalt = '') {
        if (empty($val) || empty($data) || !isset($data[$val])) {
            return $defalt;
        }
        return $data[$val];
    }

    /**
     * Ajax方式返回数据到客户端
     *
     * @access protected
     *
     * @param mixed  $data 要返回的数据
     * @param String $type AJAX返回数据格式
     *
     * @return void
     */
    protected function ajaxReturn($data, $type = '', $json_option = 0) {
        $data['referer'] = isset($data['url']) ? $data['url'] : "";
        $data['state'] = isset($data['status']) ? "success" : "fail";
        if (empty($type)) {
            $type = 'JSON';
        }
        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data, $json_option));
            case 'XML' :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            case 'AJAX_UPLOAD' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:text/html; charset=utf-8');
                exit(json_encode($data, $json_option));
            default :
                exit();
        }
    }

    /**
     * SDK 初始化连接
     */
    protected function resetToken() {
        @Session::destroy();
        $html
            = <<<  EOT
<script language='javascript'>
function sswsdk_reset(){
	window:sswsdk.resetToken();
}
sswsdk_reset();
</script>
EOT;
        echo $html;
        exit();
    }

    function getRequst() {
        $this->request = Request::instance();
        $this->rq_data = array();
        // 获取请求参数
        $_key = $this->request->param('key/s', '');
        $_data = $this->request->param('data/s', '');
        // 解析请求数据
        $_pri_path = CONF_PATH.'extra/key/rsa_private_key.pem';
        $_rq_class = new \sswsdk\response\Rsaauth(false, 0, $_pri_path);
        $_auth_key = $_rq_class->getAuthkey($_key);
        $this->authkey = $_auth_key;
        if (false == $_auth_key) {
//             return hs_player_responce('1001', '非法请求');
            return '1001';
        }
        $_rq_data = $_rq_class->getRqdata($_auth_key, $_data);
        if (false == $_rq_data) {
            return '400';
        }
        $this->rq_data = $_rq_data;
    }

    /**
     * 校验请求参数
     *
     * @return $this|void
     */
    protected function huoVerify() {
        // 获取请求参数
        $_key = $this->request->param('key/s', '');
        $_data = $this->request->param('data/s', '');
        // 解析请求数据
        $_pri_path = CONF_PATH.'extra/key/rsa_private_key.pem';
        $_rq_class = new \sswsdk\response\Rsaauth(false, 0, $_pri_path);
        $_auth_key = $_rq_class->getAuthkey($_key);
        $this->auth_key = $_auth_key;
        if (false == $_auth_key) {
            return hs_sswsdk_responce('1001', '网络连接超时');
        }
        $_rq_data = $_rq_class->getRqdata($_auth_key, $_data);
        if (false == $_rq_data) {
            return hs_sswsdk_responce('400', '请求数据非法');
        }
        $this->rq_data = $_rq_data;
        $this->rq_data['ip'] = $this->request->ip();
        /* 设置session */
        $this->se_class->setSession($this->rq_data);
        return;
    }

    /**
     * 判断用户是否登录
     *
     * @return $this
     */
    public function isUserLogin() {
        $_mem_id = $this->se_class->get('id', 'user');
        if (empty($_mem_id)) {
            return hs_sswsdk_responce('1002', '登陆已过期, 重新登录');
        }
        return $_mem_id;
    }

    /**
     * @param string $sswsdk_float_id 浮点ID
     */
    protected function isUserwapLogin($sswsdk_float_id = '') {
        if (empty($sswsdk_float_id)) {
            $_sswsdk_float_id = Cookie::get('sswsdk_float_id');
        }
        if (empty($_sswsdk_float_id)) {
            $this->resetToken();
        }
        $_ss_class = new \sswsdk\common\Authcode();
        $_str = $_ss_class->discuzAuthcode($_sswsdk_float_id, 'DECODE', Config::get('config.COOKIEKEY'));
        if (empty($_str)) {
            $this->resetToken();
        }
        list($_mem_id, $_session_id) = explode('_', $_str);
        if (empty($_mem_id) || empty($_session_id)) {
            $this->resetToken();
        }
        if (empty($_mem_id) || empty($_session_id)) {
            $this->resetToken();
        }
        $this->session_config['id'] = $_session_id;
        Session::init($this->session_config);
        $_se_mem_id = $this->se_class->get('id', 'user');
        if ($_mem_id != $_se_mem_id) {
            $this->resetToken();
        }
    }

    protected function getAgentgame($agentname) {
        if (!empty($agentname) || 'default' != $agentname) {
            return $agentname;
        }
        /* 若第一次打开，且agentgame为空或default, 通过设备确认agent_id */
        $_opent_cnt = Session::get('open_cnt', 'device');
        if (1 == $_opent_cnt) {
            /* 获取渠道 */
            $_dl_class = new \sswsdk\log\Devicelog();
            $_device_id = Session::get('device_id', 'device');
            $_app_id = Session::get('app_id', 'device');
            return $_dl_class->getAgentgame($_device_id, $_app_id);
        }
        return '';
    }
}