<?php
namespace app\index\controller;
use app\index\controller\Base;
use  think\Db;
class Index extends Base
{
    public function index()
    {
        $this->islogin();
//        $this->view->assign('title','随手玩后台管理系统');
//        $this->view->assign('hystr','欢迎使用随手玩后台管理系统v1.0');
        return  $this->view->fetch();
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function welcome(){
        $this->islogin();
        return $this->view->fetch('welcome');
    }
    public function articlelist(){
        $this->islogin();
        return $this->view->fetch('article-list');
    }
    public function piclist(){
        $this->islogin();
        return $this->view->fetch('picture-list');
    }
    public function adminlist(){
        $this->islogin();
        $data = db::table('c_user')->select();

        $this->assign('list',$data);
        $this->assign('userCount',count($data));
        return $this->view->fetch('admin-list');
    }
    public function memberlist(){
        $this->islogin();
        $data = db::table('c_member')->limit(10)->select();

        $this->assign('list',$data);
        $this->assign('userCount',count($data));
        return $this->view->fetch('member-list');
    }
}