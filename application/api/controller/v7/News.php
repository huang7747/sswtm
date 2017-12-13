<?php
/**
 * News.php UTF-8
 * 新闻 消息接口
 *
 * @date    : 2016年12月10日上午12:05:28
 * @license 这不是一个自由软件，未经授权不许任何使用和传播。
 * @author  : wuyonghong <wyh@sswsdk.com>
 * @version : sswsdk 7.0
 * @modified: 2016年12月10日上午12:05:28
 */
namespace app\api\controller\v7;

use app\common\controller\Baseapi;
use think\Db;
use think\Log;
use think\Config;

class News extends Baseapi {
    function _initialize() {
        parent::_initialize();
    }

    /* 
     * 获取资讯列表（news/list）
     * http://doc.1tsdk.com/12?page_id=425
     */
    public function index() {
        $map['page'] = $this->request->param('page/d', 0); /* 第几页 */
        $map['offset'] = $this->request->param('offset/d', 0); // 每页请求数量，默认为10
        $map['gameid'] = $this->request->param('gameid/d', 0); // 每页请求数量，默认为10
        $map['catalog'] = $this->request->param('catalog/d', 0); // 每页请求数量，默认为10
        $_rdata = $this->getNewslist($map);
        if (empty($_rdata['count'])) {
            return hs_api_responce(400, '无资讯');
        }
        return hs_api_responce(200, '请求成功', $_rdata);
    }

    /*
     * WEB资讯详情页(news/webdetail/[newsid])
     * http://doc.1tsdk.com/12?page_id=427
     */
    public function webread() {
        $map['id'] = $this->request->param('newsid/d', 0); // 资讯ID
        if (empty($map['id'])) {
            return hs_api_responce(400, '参数错误');
        }
        $field = array(
            'p.id'                                                  => 'id',
            'p.post_title'                                          => 'title',
            'p.post_content'                                        => 'content',
            "FROM_UNIXTIME(p.`post_modified`, '%Y-%m-%d %H:%i:%S')" => 'pudate',
            'p.app_id'                                              => 'gameid',
            "p.smeta"                                               => 'img',
            'p.post_author'                                         => 'author',
            'p.comment_count'                                       => 'commentcnt',
            'p.post_like'                                           => 'likecnt',
            'p.post_type'                                           => 'type'
        );
        $post_model = DB::name('posts');
        $data = $post_model->alias('p')->field($field)->where($map)->find();
        $smeta = json_decode($data['img'], true);
        $data['img'] = $smeta['thumb'];
        return hs_api_responce(200, '请求成功', $data);
    }

    /* 
     * BBS资讯列表（bbs/news/list）
     * http://doc.1tsdk.com/12?page_id=452
     */
    public function bbsindex() {
    }

    /* 
     * 获取资讯详情(news/getdetail)
     * http://doc.1tsdk.com/12?page_id=426
     */
    public function read() {
    }

    /*
 * 获取资讯列表
 */
    private function getNewslist($where = array()) {
        $map['p.post_status'] = 2; // app中的游戏
        $map['g.classify'] = $this->request->param('from');
        $data = array();
        $page = 1;
        $offset = 10;
        if (is_numeric($where['page']) && is_numeric($where['page'])) {
            $page = $where['page'];
        }
        if (!empty($where['offset']) && is_numeric($where['offset'])) {
            $offset = $where['offset'];
        }
        if (!empty($where['gameid'])) {
            $map['p.app_id'] = $where['gameid'];
        }
        // 资讯类型
        if (!empty($where['catalog'])) {
            $map['p.post_type'] = $where['catalog'];
        }
        $field = array(
            'p.id'                                         => 'id',
            'p.post_title'                                 => 'title',
            'p.app_id'                                     => 'gameid',
            "p.smeta"                                      => 'img',
            "FROM_UNIXTIME(p.`post_modified`, '%Y-%m-%d')" => 'pudate',
            'p.post_author'                                => 'author',
            'p.comment_count'                              => 'commentcnt',
            'p.post_like'                                  => 'likecnt',
            'p.post_type'                                  => 'type'
        );
        $_join = [
            [Config::get('database.prefix').'game g', 'p.app_id = g.id', 'LEFT']
        ];
        $post_model = DB::name('posts');
        $count = $post_model->alias('p')->join($_join)->where($map)->count();
        if ($count > 0) {
            $limit = $page.','.$offset;
            $data = $post_model->alias('p')->join($_join)->field($field)->page($limit)->where($map)->order('post_modified desc')
                               ->select();
            foreach ($data as $k => $v) {
                $smeta = json_decode($v['img'], true);
                if (!empty($smeta['thumb'])) {
                    if (strpos($smeta['thumb'], "/") === 0) {
                        $data[$k]['img'] = Config::get('domain.STATICSITE').$smeta['thumb'];
                    }else{
                        $data[$k]['img'] = $smeta['thumb'];
                    }
                }
            }
        }
        $rdata['count'] = $count;
        $rdata['list'] = $data;
        return $rdata;
    }
}