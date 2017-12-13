<?php
/**
 * Address.php UTF-8
 * 三级地址获取
 *
 * @date    : 2017/2/7 16:32
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use think\Db;

class Address extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /**
     * 地址
     * http://doc.1tsdk.com/43?page_id=702
     * @return $this
     */
    public function read() {
        $_map['parent_id'] = $this->request->param('pid/d', 0); /* 上级地址 */
        $_map['level'] = $this->request->param('level/d', 1); /* 级别 */
        $count = Db::name('region')->where($_map)->cache(86400)->count();
        if ($count <= 0) {
            return hs_api_responce('400', '无数据');
        }
        $_rdata['count'] = $count;
        $_field = [
            'id'=>'id',
            'name'=>'name',
            'level'=>'level',
            'parent_id'=>'pid',
        ];
        $_list = Db::name('region')->field($_field)->where($_map)->cache(86400)->select();
        $_rdata['list'] = $_list;

        return hs_api_responce('200', '查询成功', $_rdata);
    }
}