<?php
/**
 * Gameindown.php UTF-8
 * 游戏下载内部方法处理
 *
 * @date    : 2016年12月9日下午10:36:14
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月9日下午10:36:14
 */
namespace app\api\controller\v7;

use think\Config;
use app\common\controller\Base;

class Gameindown extends Base {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 游戏下载接口
     * downid 表示agent_game 中的id
     * gameid 表示 app_id
     */
    public function down($downid = 0, $gameid = 100) {
        // $url = "http://gm.16yo.cn/api/downid/1/gameid/10";
        $_downdata['app_id'] = $gameid;
        if (empty($downid)) {
            $_downdata['agent_id'] = 0;
            $_downdata['agentname'] = 'default';
        } else {
            /* 查询agentid */
            $_agent_info = $this->request->agent($downid);
            if (false == $_agent_info || $_agent_info['app_id'] != $gameid) {
                $_downdata['agent_id'] = 0;
                $_downdata['agentname'] = 'default';
            } else {
                $_downdata['app_id'] = $gameid;
                $_downdata['agent_id'] = $_agent_info['agent_id'];
                $_downdata['agentname'] = $_agent_info['agentgame'];
                $_downurl = $_agent_info['url'];
            }
        }
        if (empty($_downurl)) {
            $_gv_map['app_id'] = $gameid;
            $_gv_map['status'] = 2;
            $_gv_info = \think\Db::name('game_version')->where($_gv_map)->order('id desc')->limit(1)->select();
            if (!empty($_gv_info)) {
                $_downurl = $_gv_info[0]['packageurl'];
            }
        }
        if (empty($_downurl)) {
            return hs_api_responce(404, '未找到下载地址');
        } else {
            $_downurl = Config::get('domain.DOWNSITE').DS.$_downurl;
        }
        // 默认下载app
        $_downdata['openudid'] = $this->request->get('device_id/s', '');
        $_downdata['deviceid'] = $this->request->get('device_id/s', '');
        $_downdata['idfa'] = $this->request->get('idfa/s', '');
        $_downdata['idfv'] = $this->request->get('idfv/s', '');
        $_downdata['mac'] = $this->request->get('mac/s', '');
        $_downdata['resolution'] = $this->request->get('resolution/s', '');
        $_downdata['devicetype'] = $this->request->get('devicetype/s', '');
        $_downdata['deviceinfo'] = $this->request->get('deviceinfo/s', '');
        $_downdata['network'] = $this->request->get('network/s', '');
        $_downdata['userua'] = trim_all($this->request->server('HTTP_USER_AGENT/s', ''));
        $_downdata['create_time'] = time();
        $_downdata['ip'] = $this->request->ip();
        $_downdata['local_ip'] = $this->request->get('local_ip/s', '');
        $_rs = \think\DB::name('game_downlog')->insert($_downdata);
        if (false != $_rs) {
            $this->redirect($_downurl);
        }
    }
}