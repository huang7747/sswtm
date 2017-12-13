<?php
/**
 *系统设置
 */
namespace app\sdk\controller;
use app\sdk\controller\Base;
use think\Request;
use  think\Db;
class Sysset extends Base{
    function _initialize() {
        $this->islogin();
    }
    public function getlevellist(Request $request){
        $data = $request->param();
        $llst = Db::name('game')->select();
        Return json(['data' => $llst]);
    }
}