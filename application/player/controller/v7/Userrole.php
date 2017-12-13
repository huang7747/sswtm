<?php
/**
 * Userrole.php UTF-8
 * 玩家登陆接口
 *
 * @date    : 2016年8月18日下午9:47:10
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : api 2.0
 */
namespace app\player\controller\v7;

use app\common\controller\Baseplayer;
use think\Session;

class Userrole extends Baseplayer {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 上传玩家角色数据
     */
    function set() {
        $_key_arr = array(
            'role_type',
            'server_id',
            'server_name',
            'role_id',
            'role_name'
        );
        $this->getParams($_key_arr);
        Session::set('role', $this->rq_data['roleinfo']);
        $_mr_class = new \sswsdk\log\Memrolelog('mg_role_log');
        $_data['type'] = $this->rq_data['roleinfo']['role_type'];
        $_data['money'] = 0;
        $_mr_class->insert($_data);
        return hs_player_responce(201, '上传成功', '', $this->auth_key);
    }
}