<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17 0017
 * Time: 13:48
 */

namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use think\Session;
use  think\Db;

class User extends Base
{
    public function login()
    {
        $this->alreadylogin();
        return $this->view->fetch();
    }
    public function checklogin(Request $request)
    {
        $status=0;
        $result='';
        $data=$request->param();
        //创建验证规则
        $rule=[
            'name|用户名'=>'require',
            'password|密码'=>'require',
//            'verify|验证码'=>'require|captcha',
        ];

//        自定义消息
        $msg=[
            'name'=>['require'=>'用户名不能为空，请检查！'],
            'password'=>['require'=>'密码不能为空，请检查！'],
//            'verify'=>[
//                'require'=>'验证码不能为空，请检查！',
//                'captcha'=>'验证码验证失败，请检查！'
//            ],
        ];
        //进行验证
        $result=$this->validate($data,$rule,$msg);

        if ($result===true){
            $map=[
                'user_login'=>$data['name'],
                'user_pass'=>md5($data['password']),
            ];
            //查询用户信息
            $user=Db::name('users')->where($map)->find();
            if ($user==null){
                $status=0;
                $result= "没有找到该用户,请检查用户名或者密码！";
            }
            else{
                $status=1;
                Session::set('user_id',$user['user_login']);
//                $user->setField('login_time','date()');
            }
        }

        Return ['status'=>$status,'messages'=>$result,'data'=>$data];

    }
    public function loginout()
    {

        Session::delete('user_id');
        Session::delete('user_info');
        Session::destroy();

//        $this->redirect('http://www.myweb.cn/index.php/index/user/login');
        $this->success('注销登录，正在返回','User/login');
    }
}