<?php
/**
 * Aboutus.php UTF-8
 * 关于我们
 *
 * @date    : 2017/3/2 21:31
 *
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 */
namespace app\api\controller\v7;

use think\Controller;
use think\Db;

class Aboutus extends Controller {
    function _initialize() {
        parent::_initialize();
    }

    /*
     * 关于我们(system/aboutus)
     * http://doc.1tsdk.com/43?page_id=825
     */
    public function index() {
//        $_rdata = \think\Db::name('options')->where('option_name', 'appaboutus')->value('option_value');
        $_rdata = $this->test();

        return hs_api_responce('200', '请求成功', json_decode($_rdata, true));
    }

    public function test() {
        $record = DB::name('options')->field("option_value")->where(['option_name' => 'company_info'])->find();
        $info_data = json_decode($record['option_value'], true);
        $data['wburl'] = $info_data['wburl'];
        $data['website'] = $info_data['website'];
        $data['qq'] = [
            [
                'name'   => '1',
                'number' => '1'
            ],
            [
                'name'   => '2',
                'number' => '2'
            ],
            [
                'name'   => '3',
                'number' => '3'
            ],
            [
                'name'   => '4',
                'number' => '4'
            ]
        ];
        $data['qqgroup'] = [
            [
                'name'   => '1',
                'number' => '1',
                'key'    => '1',
                'status' => '1'
            ],
            [
                'name'   => '2',
                'number' => '2',
                'key'    => '2',
                'status' => '2'
            ], [
                'name'   => '3',
                'number' => '3',
                'key'    => '3',
                'status' => '3'
            ], [
                'name'   => '4',
                'number' => '4',
                'key'    => '4',
                'status' => '4'
            ],
            [
                'name'   => '5',
                'number' => '5',
                'key'    => '5',
                'status' => '5'
            ]
        ];
        $data['tel'] = [
            [
                'number' => '1',
            ],
            [
                'number' => '2',
            ],
            [
                'number' => '3',
            ],
            [
                'number' => '4',
            ]
        ];
        $data['servicetime'] = [
            [
                'weekday' => '9:00-18:00',
                'holiday' => '21:00-23:00',
            ]
        ];

        return json_encode($data);
    }
}