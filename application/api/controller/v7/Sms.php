<?php
/**
 * Sms.php UTF-8
 * 短信接口
 *
 * @date    : 2016年8月18日下午9:47:10
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : api 2.0
 */
namespace app\api\controller\v7;

use app\common\controller\Basehuo;

class Sms extends Basehuo {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 短信发送
     */
    function send() {
        $_key_arr = array(
            'app_id',
            'client_id',
            'from',
            'device_id',
            'userua',
            'mobile',
            'smstype'
        );
        $_param_data = $this->getParams($_key_arr);
        $_mobile = $_param_data['mobile'];
        $_smstype = $_param_data['smstype'];
        $_username = get_val($this->rq_data, 'username', '');
        /* 非注册发送验证码 需要有效用户名 */
        if (1 != $_smstype && empty($_username)) {
            $_username = \think\Session::get('username', 'user');
            if (empty($_username)) {
                $_map['mobile'] = $_mobile;
                $_username = \think\Db::name("members")->where($_map)->value('username');
                if (empty($_username)) {
                    return hs_sswsdk_responce(413, '手机号输入错误');
                }
            }
        }
        $_sms_class = new \sswsdk\sms\Sms();
        $_data = $_sms_class->send($_mobile, $_smstype);
        $_rdata['agentgame'] = $this->getVal($_param_data, 'agentgame', '');
        $_rdata['agentgame'] = $this->getAgentgame($_rdata['agentgame']);

        return hs_player_responce($_data['code'], $_data['msg'], $_rdata, $this->auth_key);
    }
}