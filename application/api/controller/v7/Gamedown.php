<?php
/**
 * Gamedown.php UTF-8
 * 游戏下载
 *
 * @date    : 2016年12月3日下午4:13:57
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月3日下午4:13:57
 */
namespace app\api\controller\v7;

use sswsdk\agent\Agent;
use think\Config;
use app\common\controller\Base;

class Gamedown extends Base {
    function _initialize() {
        parent::_initialize();
        \think\Request::hook('agent', 'get_ag_info');
    }

    /*
     * 游戏下载接口
     * downid 表示agent_game 中的id
     * gameid 表示 app_id
     */
    public function down($downid = 0, $gameid = 0) {
        if (empty($gameid)) {
            $gameid = Config::get('config.APP_APPID');
        }
        $_rq_data['device_id'] = $this->request->get('device_id/s', '');
        $_rq_data['idfa'] = $this->request->get('idfa/s', '');
        $_rq_data['idfv'] = $this->request->get('idfv/s', '');
        $_rq_data['mac'] = $this->request->get('mac/s', '');
        $_rq_data['resolution'] = $this->request->get('resolution/s', '');
        $_rq_data['devicetype'] = $this->request->get('devicetype/s', '');
        $_rq_data['deviceinfo'] = $this->request->get('deviceinfo/s', '');
        $_rq_data['network'] = $this->request->get('network/s', '');
        $_rq_data['userua'] = trim_all($this->request->server('HTTP_USER_AGENT/s', ''));
        $_rq_data['ip'] = $this->request->ip();
        $_rq_data['local_ip'] = $this->request->get('local_ip/s', '');
        $_gd_class = new \sswsdk\game\Gamedown();
        $_downurl = $_gd_class->down($_rq_data, $downid, 0,$gameid);
        if (false != $_downurl) {
            $this->redirect($_downurl);
        }
        return;
    }

    /* 
     * 【内】游戏下载地址(game/down)
     * http://doc.1tsdk.com/43?page_id=625
     */
    public function geturl() {
        $this->huoVerify();
        $_rq_data = $this->getParams();
        $_game_id = get_val($_rq_data,'gameid',0);
        if (empty($_game_id)) {
            return hs_sswsdk_responce('436', '游戏ID错误', null);
        }
        $_agent_class = new Agent(0,$_rq_data['agentgame']);

        $_agent_id = $_agent_class->getAgentid();
        $_rq_data['device_id'] = get_val($_rq_data,'device_id','');
        $_rq_data['userua'] = trim_all(get_val($_rq_data,'userua',''));
        $_rq_data['ipaddrid'] = get_val($_rq_data,'ipaddrid','');
        $_rq_data['deviceinfo'] = get_val($_rq_data,'deviceinfo','');
        $_rq_data['idfv'] = get_val($_rq_data,'idfv','');
        $_rq_data['idfa'] = get_val($_rq_data,'idfa','');
        $_rq_data['local_ip'] = get_val($_rq_data,'local_ip','');
        $_rq_data['mac'] = get_val($_rq_data,'mac','');
        $_rq_data['ip'] = $this->request->ip();

        $_gd_class = new \sswsdk\game\Gamedown();
        $_downurl = $_gd_class->down($_rq_data, 0, $_agent_id,$_game_id);

        $_downcnt = $_gd_class->getDowncnt($_game_id);
        $_rdata['downcnt'] = $_downcnt;
        $_rdata['count'] = 1;
        $_list[0]['type'] = '1';
        $_list[0]['name'] = '本地下载';
        $_list[0]['url'] = $_downurl;
        $_rdata['list'] = $_list;

        return hs_sswsdk_responce(200,'查询成功',$_rdata,$this->auth_key);
    }
}