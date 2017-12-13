<?php
/**
 * User.php UTF-8
 * 玩家接口
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
use sswsdk\log\Memlog;
use sswsdk\player\Meminfo;
use sswsdk\sms\Verify;
use think\Config;
use think\Db;
use think\Session;

class User extends Basehuo {
    protected $m_class;

    function _initialize() {
        parent::_initialize();
        $this->m_class = new \sswsdk\player\Member();
    }

    /**
     *
     * 注册登陆后,获得返回数据
     *
     * @param        $mem_info
     * @param string $agentgame
     * @param int    $flag
     *
     * @return mixed
     */
    protected function getReturn($mem_info, $agentgame = '', $flag = 0) {
        $_rdata['user_token'] = $this->se_class->setUsertoken($mem_info['id'], $this->rq_data);
        Session::set('agentgame', $agentgame, 'user');
        //登陆处理
        $_login_data['mem_id'] = Session::get('id', 'user');
        $_login_data['app_id'] = Session::get('app_id', 'app');
        $_login_data['agentgame'] = $agentgame;
        $_login_data['imei'] = Session::get('device_id', 'device');
        $_login_data['deviceinfo'] = Session::get('deviceinfo', 'device');
        $_login_data['userua'] = Session::get('userua', 'device');
        $_login_data['from'] = Session::get('from', 'device');
        $_login_data['flag'] = $flag;
        $_login_data['reg_time'] = $mem_info['reg_time'];
        $_login_data['login_time'] = time();
        $_login_data['agent_id'] = Session::get('agent_id', 'user');
        $_login_data['login_ip'] = $this->request->ip();
        $_login_data['ipaddrid'] = Session::get('ipaddrid', 'device');
        $_login_data['open_cnt'] = Session::get('open_cnt', 'device');
        $_login_class = new Memlog('login_log');
        $_login_class->login($_login_data);
        $_rdata['mem_id'] = $_login_data['mem_id'];
        $_ss_class = new Simplesec();
        $_rdata['cp_user_token'] = $_ss_class->encode(session_id(), Config::get('config.CPAUTHCODE'));
        $_rdata['agentgame'] = $agentgame;
        //20170523 sdk登陆新增返回
        $_rdata['nickname'] = $mem_info['nickname'];
        $_rdata['portrait'] = $mem_info['portrait'] ? Config::get('domain.STATICSITE').$mem_info['portrait'] : '';
        $_rdata['menu'] = [ //浮点菜单
                            [
                                'name' => '消息',
                                'type' => 'message',
                                'icon' => Config::get('domain.STATICSITE').'/upload/0/message.png',
                                'url'  => Config::get('domain.SDKSITE')."/float.php/Mobile/Message/mymsg",
                            ],
                            [
                                'name' => '礼包',
                                'type' => 'gift',
                                'icon' => Config::get('domain.STATICSITE').'/upload/0/gift.png',
                                'url'  => Config::get('domain.SDKSITE')."/float.php/Mobile/Gift/index",
                            ],
                            [
                                'name' => '工具',
                                'type' => 'tools',
                                'icon' => Config::get('domain.STATICSITE').'/upload/0/tools.png',
                                'url'  => Config::get('domain.SDKSITE')."/float.php/Mobile/Gift/index",
                            ],
                            [
                                'name' => '客服',
                                'type' => 'service',
                                'icon' => Config::get('domain.STATICSITE').'/upload/0/service.png',
                                'url'  => Config::get('domain.SDKSITE')."/float.php/Mobile/Help/index",
                            ],
                            [
                                'name' => '实名认证',
                                'type' => 'indentify',
                                'icon' => Config::get('domain.STATICSITE').'/upload/0/indentify.png',
                                'url'  => Config::get('domain.SDKSITE')."/float.php/Mobile/Help/index",
                            ],
                            [
                                'name' => '游戏中心',
                                'type' => 'gamecenter',
                                'icon' => Config::get('domain.STATICSITE').'/upload/0/gamecenter.png',
                                'url'  => Config::get('domain.SDKSITE')."/float.php/Mobile/Help/index",
                            ],
                            [
                                'name' => '个人',
                                'type' => 'gamecenter',
                                'icon' => Config::get('domain.STATICSITE').'/upload/0/gamecenter.png',
                                'url'  => Config::get('domain.SDKSITE')."/api/v7/web/user/index",
                            ],
        ];

        return $_rdata;
    }

    protected function checkMobile($mobile = '', $sms_code = '') {
        $_mobile = $mobile;
        if (empty($_mobile)) {
            $_mobile = get_val($this->rq_data, 'mobile', '');
        }
        $_sms_code = $sms_code;
        if (empty($_sms_code)) {
            $_sms_code = get_val($this->rq_data, 'smscode', '');
        }
        $_sv_class = new Verify($_mobile, $_sms_code, 1);
        $_check_data = $_sv_class->check();
        if ('200' != $_check_data['code']) {
            return hs_player_responce($_check_data['code'], $_check_data['msg']);
        }

        return true;
    }

    protected function logout() {
        Session::clear('user');
        Session::clear('order');
        Session::clear('role');
    }

    /**
     * http://doc.1tsdk.com/43?page_id=1503
     * 【内】找回密码用户信息获取( user/info )
     * portrait
     * nickname
     * myintegral
     * couponcnt
     * giftcnt
     * gmgamecnt
     * newmsg
     */
    public function redinfo() {
        $_username = $this->getVal($this->rq_data, 'username', '');
        $_sms_class = new \sswsdk\sms\Sms();
        $_is_mobile = $_sms_class->checkMoblie($_username);
        if ($_is_mobile) {
            $_map['bindmobile'] = $_username;
        } else {
            $_map['username|email'] = $this->getVal($this->rq_data, 'username', '');
        }
        $_field = [
            'username' => 'username',
            'email'    => 'email',
            'mobile'   => 'mobile',
            'nickname' => 'nickname',
            'portrait' => 'portrait'
        ];
        $_mem_info = Db::name('members')->field($_field)->where($_map)->find();
        if (empty($_mem_info)) {
            return hs_sswsdk_responce('300', '获取信息失败');
        } else {
            if (!empty($_mem_info['mobile'])) {
                $_mem_info['mobile'] = preg_replace('/(^.*)\d{4}(\d{4})$/', '\\1****\\2', $_mem_info['mobile']);
            }
            if (!empty($_mem_info['email'])) {
                $_str = $_mem_info['email'];
                $_email_array = explode("@", $_str);
                $_prev_fix = (strlen($_email_array[0]) < 4) ? "" : substr($_str, 0, 3); // 邮箱前缀
                $_count = 0;
                $_str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $_str, -1, $_count);
                $_mem_info['email'] = $_prev_fix.$_str;
            }
        }

        return hs_sswsdk_responce('200', '请求成功', $_mem_info, $this->auth_key);
    }

    /**
     * http://doc.1tsdk.com/43?page_id=691
     * 【内】获取用户信息( user/detail )
     * portrait
     * nickname
     * myintegral
     * couponcnt
     * giftcnt
     * gmgamecnt
     * newmsg
     */
    public function read() {
        $_mem_id = $this->isUserLogin();
        $_mi_class = new Meminfo($_mem_id);
        $_rdata = $_mi_class->read();
        if (empty($_rdata)) {
            return hs_sswsdk_responce('400', '获取信息失败');
        }

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】读取玩家地址(user/address/detail)
     * http://doc.1tsdk.com/43?page_id=698
     *
     * @return $this 返回地址信息
     */
    public function redaddress() {
        $_mem_id = $this->isUserLogin();
        $_mi_class = new Meminfo($_mem_id);
        $_rdata = $_mi_class->getAddress();

        return hs_sswsdk_responce('200', '请求成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】修改玩家地址(user/address/update)
     * http://doc.1tsdk.com/43?page_id=701
     *
     */
    public function setaddress() {
        $_mem_id = $this->isUserLogin();
        $_key_arr = [
            'consignee',
            'mobile',
            'province',
            //            'city',
            //            'town',
            'address'
        ];
        $_data = $this->getParams($_key_arr);
        $_mi_class = new Meminfo($_mem_id);
        $_rdata = $_mi_class->setAddress($_data);
        if (false == $_rdata) {
            return hs_sswsdk_responce('400', '修改地址信息失败');
        }

        return hs_sswsdk_responce('200', '修改成功');
    }

    /**
     * http://doc.1tsdk.com/43?page_id=693
     * 【内】找回密码( user/passwd/find )
     */
    public function setpwd() {
        $_mobile = get_val($this->rq_data, 'mobile', '');
        $_email = get_val($this->rq_data, 'email', '');
        $_code = get_val($this->rq_data, 'code', '');
        $_new_pwd = get_val($this->rq_data, 'password', '');
        if (empty($_mobile) && empty($_email)) {
            return hs_sswsdk_responce(400, '参数错误');
        } else if (!empty($_mobile)) {
            $this->checkMobile($_mobile, $_code);
            $_mi_class = new Meminfo();
            $_rs = $_mi_class->setPwdByMobile($_mobile, $_new_pwd);
        } else {
            $this->checkEmailCode($_email, $_code);
            $_mi_class = new Meminfo();
            $_rs = $_mi_class->setPwdByEmail($_email, $_new_pwd);
        }
        if (false == $_rs) {
            return hs_sswsdk_responce(1000, '修改失败');
        }

        return hs_sswsdk_responce(200, '修改成功', ['status' => 2], $this->auth_key);
    }

    /**
     * 【内】修改密码( user/passwd/update )
     * http://doc.1tsdk.com/43?page_id=657
     */
    public function modpwd() {
        $_mem_id = $this->isUserLogin();
        $_old_pwd = get_val($this->rq_data, 'old_pwd', '');
        $_new_pwd = get_val($this->rq_data, 'new_pwd', '');
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->modPwd($_old_pwd, $_new_pwd);
        if (200 != $_rs) {
            return hs_sswsdk_responce($_rs, '修改失败');
        }

        return hs_sswsdk_responce(200, '修改成功', null, $this->auth_key);
    }

    /**
     * 【内】修改玩家信息(user/info/update)
     * http://doc.1tsdk.com/43?page_id=700
     */
    public function set() {
        $_mem_id = $this->isUserLogin();
        $_nicename = get_val($this->rq_data, 'nicename', '');
        if (empty($_nicename)) {
            return hs_sswsdk_responce(400, '请填写昵称');
        }
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->setNicename($_nicename);
        if (false === $_rs) {
            return hs_sswsdk_responce(400, '修改失败');
        }

        return hs_sswsdk_responce(200, '修改成功');
    }

    /**
     * 【内】绑定手机( user/phone/bind )
     * http://doc.1tsdk.com/43?page_id=658
     *
     * @return $this
     */
    public function bindmobile() {
        $_mem_id = $this->isUserLogin();
        $this->checkMobile();
        $_mobile = get_val($this->rq_data, 'mobile', '');
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->setMobile($_mobile);
        if (false == $_rs) {
            return hs_sswsdk_responce(400, '绑定手机已存在');
        }
        $_rdata['status'] = 2;

        return hs_sswsdk_responce(200, '绑定手机成功', $_rdata, $this->auth_key);
    }

    /**
     * 手机解绑
     * http://doc.1tsdk.com/43?page_id=1285
     */
    public function remove_bindmobile() {
        $_mem_id = $this->isUserLogin();
        $this->checkMobile();
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->unsetMobile();
        if (false == $_rs) {
            return hs_sswsdk_responce(400, '您已经解除绑定了');
        }
        $_rdata['status'] = 2;

        return hs_sswsdk_responce(200, '解除绑定成功', $_rdata, $this->auth_key);
    }

    /**
     * 发送邮箱验证码 (user/email/send)
     * http://doc.1tsdk.com/43?page_id=1282
     */
    public function send_email() {
//        $this->isUserLogin();
        $_email = get_val($this->rq_data, 'email', '');
        $_type = get_val($this->rq_data, 'type', 0);
        /* 超时时间为 30min 1800s */
        $_diff_time = 30 * 60;
        $_email_class = new \sswsdk\email\Email($_diff_time);
        $_username = get_val($this->rq_data, 'username', '');
        if (empty($_username)) {
            $_username = Session::get('username', 'user');
            if (empty($_username)) {
                $_map['email'] = $_email;
                $_username = Db::name("members")->where($_map)->value('username');
                if (empty($_username)) {
                    return hs_sswsdk_responce(413, '邮箱错误');
                }
            }
        }
        $_rdata = $_email_class->send($_email, $_username, $_type);
        if (200 == $_rdata['code']) {
            $_rd['status'] = 2;

            return hs_sswsdk_responce(200, $_rdata['msg'], $_rd, $this->auth_key);
        } else {
            // 邮件发送失败
            return hs_sswsdk_responce($_rdata['code'], $_rdata['msg']);
        }
    }

    /**
     * 校验邮箱验证码
     *
     * @param $email
     * @param $code
     *
     * @return bool
     */
    public function checkEmailCode($email = '', $code = '') {
        $_email = $email;
        $_code = $code;
        if (empty($_email)) {
            $_email = get_val($this->rq_data, 'email', '');
        }
        if (empty($_code)) {
            $_code = get_val($this->rq_data, 'code', '');
        }
        $_email_class = new \sswsdk\email\Email();
        $_rs = $_email_class->check($_email, $_code);
        if ($_rs['code'] != 200) {
            return hs_sswsdk_responce($_rs['code'], $_rs['msg']);
        }

        return true;
    }

    /**
     * http://doc.1tsdk.com/43?page_id=1283
     * 【内】绑定邮箱
     *
     * @return $this
     */
    public function bindemail() {
        $_mem_id = $this->isUserLogin();
        $_email = get_val($this->rq_data, 'email', '');
        $_code = get_val($this->rq_data, 'code', '');
        $this->checkEmailCode($_email, $_code);
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->setEmail($_email);
        if (false == $_rs) {
            return hs_sswsdk_responce(400, '请勿重复绑定');
        }
        $_rdata['status'] = 2;

        return hs_sswsdk_responce(200, '绑定成功', $_rdata, $this->auth_key);
    }

    /**
     * 邮箱解绑
     * http://doc.1tsdk.com/43?page_id=1284
     */
    public function remove_bindemail() {
        $_mem_id = $this->isUserLogin();
        $this->checkEmailCode();
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->unsetEmail();
        if (false == $_rs) {
            return hs_sswsdk_responce(400, '您已解绑');
        }
        $_rdata['status'] = 2;

        return hs_sswsdk_responce(200, '解除绑定成功', $_rdata, $this->auth_key);
    }

    /**
     * 【内】验证手机( user/phone/verify )
     * http://doc.1tsdk.com/43?page_id=803
     *
     * @return $this
     */
    public function verify() {
        $_mem_id = $this->isUserLogin();
        $this->checkMobile();
        $_mobile = get_val($this->rq_data, 'mobile', '');
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->verifyMobile($_mobile);
        if (false === $_rs) {
            return hs_sswsdk_responce(400, '手机验证不通过');
        }
        $_rdata['status'] = 2;

        return hs_sswsdk_responce(200, '验证成功', $_rdata, $this->auth_key);
    }

    /**
     * http://doc.1tsdk.com/43?page_id=1107
     * 【内】添加邀请码( user/introducer/addcode )
     */
    public function setintroducer() {
        $this->isUserLogin();
        $introducer = get_val($this->rq_data, 'introducer', '');
        $_mem_id = Session::get('id', 'user');
        $_mi_class = new Meminfo($_mem_id);
        $_rs = $_mi_class->setintroducer($introducer);
        if (401 == $_rs) {
            return hs_sswsdk_responce(401, '请输入正确的邀请人');
        }
        if (402 == $_rs) {
            return hs_sswsdk_responce(402, '已经绑定过邀请人了');
        }
        if (403 == $_rs) {
            return hs_sswsdk_responce(403, '邀请人不存在');
        }
        if (404 == $_rs) {
            return hs_sswsdk_responce(404, '系统内部错误');
        }

        return hs_sswsdk_responce(200, '修改成功', null, $this->auth_key);
    }
}