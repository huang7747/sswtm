<?php
/*
**游戏管理
**/
namespace app\sdk\controller;
use app\sdk\controller\Base;
use think\Request;
use  think\Db;
class Game extends Base
{
    public function addgame(Request $request){

        if ($this->islogin()){
            $data = $request->param();
            $game_data['name'] = $data['appname'];
            $game_data['classify'] = 3;
            $game_data['cooperation']=$data['appcp'];
            /**
             * 刚添加游戏的时候，游戏的状态肯定是接入中
             */
            $game_data['status'] = 1;
            $current_time = time();
            $game_data['create_time'] = $current_time;
            $game_data['update_time'] = $current_time;
            /* 检测输入参数合法性, 游戏名 */
            if (!isset($game_data['name'])) {
                $status=0;
                $result='游戏名称不能为空！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
                exit;
            }
            $type='and';
            // 获取游戏名称拼音
            vendor('Pin#class');
    //        import('Vendor.Pin');
            $pin = new \Pin();
            $game_data['gameflag'] = $pin->pinyin($game_data['name']);
            $game_data['pinyin'] = $pin->pinyin($game_data['name'].$type);
            $game_data['initial'] = $pin->pinyin($game_data['name'].$type, true);
            $checkgame = Db::name('game')->where(array('pinyin' => $game_data['pinyin']))->find();
            if (!empty($checkgame)) {
                if ($checkgame['is_delete'] == 1) {
                    $status=0;
                    $result='游戏在删除列表已经存在！';
                    Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
                    exit;
                }
                else{
                    $status=0;
                    $result='游戏已经存在！';
                    Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
                    exit;
                }
            }
            $version = '1.0';
            $app_id = Db::name('game')->insertGetId($game_data);
            /* 插入游戏类型  */
            if ($app_id > 0) {
                $update_data['app_key'] = md5($app_id.md5($game_data['pinyin'].$game_data['create_time']));
                $update_data['initial'] = $game_data['initial'].'_'.$app_id;
                $update_data['id'] = $app_id;
                /* 查询game_id是否存在 */
                $update_data['game_id'] =Db::name('game')
                    ->where(array('gameflag' => $game_data['gameflag']))
                    ->value('game_id');
                if (empty($update_data['game_id'])) {
                    $update_data['game_id'] = $app_id;
                }
                Db::name('game')->where('id','=',$app_id)->update($update_data);
                //游戏版本插入
                $gv_data['app_id'] = $app_id;
                $gv_data['version'] = $version;
                $gv_data['create_time'] = $game_data['create_time'];
                $gv_id =Db::name('game_version') ->insertGetId($gv_data);
                //client_id 操作
                $gc_data['app_id'] = $app_id;
                $gc_data['version'] = $version;
                $gc_data['client_key'] = md5($version.md5($game_data['initial'].rand(10, 1000)));
                $gc_data['gv_id'] = $gv_id;
                $gc_data['gv_new_id'] = $gv_id;
                Db::name('game_client')->insert($gc_data);
                $status=1;
                $result='游戏添加成功！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
            }
            else {
                $status=0;
                $result='游戏添加失败！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
            }
        }
        else{
                $status = 500;
                $result = '用户没有登录！';
                Return json(['status' => $status, 'messages' => $result]);
        }
    }
    /**
     * 游戏删除恢复
     */
    public function set_game (Request $request)
    {
        if ($this->islogin()){
            $data = $request->param();
            $status = 0;
            $rs = Db::name('game')
                ->where('id', $data['appid'])
                ->setField('is_delete', $data['isdel']);
            if ($rs) {
                $status = 1;
                $result = '状态切换成功！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
                exit;
            }
            else
            {
                $result = '状态切换失败！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
                exit;
            }
        }
        else{
            $status = 500;
            $result = '用户没有登录！';
            Return json(['status' => $status, 'messages' => $result]);
        }
    }
    /**
     * 游戏上下线
     */
    public function set_status(Request $request) {
        if ($this->islogin()){
            $status=0;
            $data=$request->param();
            if (empty($data['status'])) {
                $result='状态错误';
                Return json(['status'=>$status,'messages'=>$result,'data'=>$data]);
                exit;
            }
            if (2 == $data['status']) {
                $g_data = Db::name('game')->where('id','=', $data['id'])->find();
                if (empty($g_data['cpurl'])) {
                    $result='请填写回调地址！';
                    Return json(['status'=>$status,'messages'=>$result,'data'=>$data]);
                    exit;
                }
                $gv_id = Db::name('game_client')->where('app_id','=', $data['id'])->value('gv_id');
                $packageurl = Db::name('game_version')->where('id','=', $gv_id)->value('packageurl');
                if (empty($packageurl)) {
                    $result='请上传母包！';
                    Return json(['status'=>$status,'messages'=>$result,'data'=>$data]);
                    exit;
                }
            }
            $data['run_time'] = time();
            $map = array('status'=>$data['status'],'run_time'=>$data['run_time']);
            $rs = Db::name('game')
                ->where('id','=', $data['id'])
                ->setField($map);
            if ($rs) {
                $status=1;
                $result='状态切换成功！';
                Return json(['status'=>$status,'messages'=>$result,'data'=>$data]);
                exit;
            } else {
                $result='状态切换失败,请检查！';
                Return json(['status'=>$status,'messages'=>$result,'data'=>$data]);
                exit;
            }
        }
        else{
            $status = 500;
            $result = '用户没有登录！';
            Return json(['status' => $status, 'messages' => $result]);
        }
    }

    /**
     * 修改游戏回调
     */
    public function editurl(Request $request) {
        if ($this->islogin()){
            $data = $request->param();
            $rs = Db::name('game')
                ->field('id app_id,name gamename,cpurl')
                ->where('id','=', $data['appid'])
                ->select();
            $status = 1;
            $result = '回调成功！';
            Return json(['status' => $status, 'messages' => $result, 'data' => $rs]);
       }
        else{
            $status = 500;
            $result = '用户没有登录！';
            Return json(['status' => $status, 'messages' => $result]);
        }
    }

    /**
     * 查找游戏
     */
    public function findgame(Request $request)
    {
        if ($this->islogin()){
            $data = $request->param();
            $map['a.is_delete']=$data['isdel'];
            $map['a.name']=$data['appname'];
            $rs=Db::name('game a')
                ->field('a.id, a.icon,a.name,a.cooperation,a.status,a.run_time,a.cpurl,a.initial,g.packageurl')
                ->join('game_version g', 'a.id=g.app_id', 'left')
                ->where($map)
                ->select();
            $status = 1;
            $result = '游戏查找完成！';
            Return json(['status' => $status, 'messages' => $result, 'data' => $rs]);
        }
        else{
            $status = 500;
            $result = '用户没有登录！';
            Return json(['status' => $status, 'messages' => $result]);
        }

    }
    /**
     * 修改游戏回调
     */
    public function set_callback (Request $request)
    {
        if ($this->islogin()){
            $data = $request->param();
            $rs = Db::name('game')
                ->where('id', $data['appid'])
                ->setField('cpurl', $data['cpurl']);
            if ($rs) {
                $status = 1;
                $result = '回调修改完成！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
                exit;
            }
            else
            {
                $status = 0;
                $result = '回调修改失败！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $data]);
                exit;
            }
        }
        else{
            $status = 500;
            $result = '用户没有登录！';
            Return json(['status' => $status, 'messages' => $result]);
        }
    }
    /**
     * 添加游戏母包
     */
    public function addpackageurl(Request $request){
        if ($this->islogin()){
            $data = $request->param();
            $games = Db::name('game_version')->where('app_id','=',$data['appid'])->select();
            $initial = Db::name('game')->where('id','=',$data['appid'])->value('initial');;
            if (empty($games)) {
                $status =0;
                $result = '请生成参数对接后,再添加游戏母包地址！';
                Return json(['status' => $status, 'messages' => $result,'data' => $data]);
                exit;
            }
            $opt = md5(md5($initial.$initial).'resub');
            $pinyin = base64_encode($initial);
            $agentgame = base64_encode($initial);
            $opt = base64_encode($opt);
            $data_string = array('p' => $pinyin, 'a' => $agentgame, 'o' => $opt);
//            $data_string['ipa_url'] = DOWNSITE.'/'.$initial.'/'.$initial.'.ipa';
            $data_string = json_encode($data_string);
            $url = DOWNIP."/sub.php";
            $cnt = 0;
            $flag = false;
            while (1) {
                $return_content = base64_decode(http_post_data($url,$data_string));
                if (!is_int($return_content) && strlen($return_content) > 20) {
                    $flag = true;
                    break;
                }
                if (0 < $return_content || 3 == $cnt) {
                    break;
                }
                $cnt++;
            }
            //若存在则更新地址
            if (true == $flag) {
                $games['packageurl'] = $initial.'/'.$initial.'.apk';
                Db::name('game_version')->update($games);
                $apkdata = (array)json_decode($return_content);
                if (!empty($apkdata)) {
                    if (empty($apkdata['size'])) {
                        $apkdata['size'] = 0;
                    }
                    //游戏版本插入
                    $games['version'] = $apkdata['vername'];
                    $games['size'] = $apkdata['size'];
                    $gv_id = Db::name('game_version')->update($games);
                    $gi_info = Db::name('game_info')->where(array('app_id' => $data['appid']))->find();
                    $gi_info['androidurl'] = DOWNSITE.''.$games['packageurl'];
                    $gi_info['size'] = format_file_size($apkdata['size']);
                    if (empty($gi_info['app_id'])) {
                        $downurl['android']['local'] = $gi_info['androidurl'];
                        $gi_info['downurl'] = json_encode($downurl);
                        $gi_info['app_id'] = $appid;
                        Db::name('game_info')->insert($gi_info);
                    } else {
                        Db::name('game_info')->update($gi_info);
                    }
                    //游戏报名插入
                    Db::name('game')->where(array('id' => $data['appid']))->setField('packagename', $apkdata['pakagename']);
                }
                $status = 1;
                $result = '母包生成成功！';
                Return json(['status' => $status, 'messages' => $result, 'data' => $games]);
            }

        }
        else{
            $status = 500;
            $result = '用户没有登录！';
            Return json(['status' => $status, 'messages' => $result]);
        }
    }

    /*
     * 获取对接参数
     */
    public function get_param(Request $request) {
        if ($this->islogin()){
            $data = $request->param();
            $param =Db::name('game')->field('id app_id, name gamename, app_key')->where('id','=', $data['appid'])->find();
            $client = Db::name('game_client')->field('id client_id,client_key')->where('app_id','=', $data['appid'])->find();
            $rs = array_merge($param, $client);
            $status = 1;
            $result = '游戏对接参数修改完成！';
            Return json(['status' => $status, 'messages' => $result, 'data' => $rs]);
        }
        else{
            $status = 500;
            $result = '用户没有登录！';
            Return json(['status' => $status, 'messages' => $result]);
        }
    }
}