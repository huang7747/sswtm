<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17 0017
 * Time: 18:36
 */
namespace app\index\model;
use think\Model;
class User extends Model
{
    public function updateState(){
        $Dao = M("User");

        $result = $Dao->where('uid = 2')->setField('email','Jack@163.com');

        if($result !== false){
            echo '数据更新成功！';
        }else{
            echo '没更新任何数据！';
        }
    }
}