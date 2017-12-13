<?php
namespace app\common\controller;
class Baseplayer extends Base {
    protected $mem_id;

    protected function _initialize() {
        parent::_initialize();
        $this->huoVerify();
        $this->isUserLogin();
        $this->mem_id = $this->se_class->get('id', 'user');
    }
}