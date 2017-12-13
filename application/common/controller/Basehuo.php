<?php
/**
 * Basehuo.php UTF-8
 * 火速内部方法引用头文件
 *
 * @date    : 2017/1/16 14:09
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\common\controller;
class Basehuo extends Base {
    protected function _initialize() {
        parent::_initialize();
        $this->huoVerify();
    }
}