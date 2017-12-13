<?php
namespace app\sdk\controller;
use  think\Controller;
use think\Session;
class Base extends Controller
{
    protected function islogin(){
        if (empty(Session::get('user_id'))) {
            return false;
        }
        else
        {
            return true;
        }
    }
    protected function alreadylogin(){
        if (!empty(Session::get('user_id'))){
            $this->redirect(WEBSITE.'/index.php/index/index/index');
        }
    }
}
