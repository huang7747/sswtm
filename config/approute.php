<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
return [
    /* 系统 */
    ':version/system/appinit$'           => 'api/:version.System/init', /* 初始化  (half) */
    ':version/system/get_server_time$'   => 'api/:version.System/time', /* 服务器时间  */
    ':version/system/get_help_info$'     => 'api/:version.System/help', /* 客服信息  */
    ':version/system/has_new_version$'   => 'api/:version.System/newVersion', /* 是否有新版本  */
    ':version/system/get_version_info$'  => 'api/:version.System/version', /* 版本信息  */
    ':version/system/get_splash$'        => 'api/:version.System/splash', /* 开机闪屏图 */
    ':version/homepage$'                 => 'api/:version.Homepage/index', /* 首页 */
    /* 游戏 */
    'game/wap/detail$'                   => 'wap/Game/read', /* 游戏详情wap页面 */
    ':version/game/gametype$'            => 'api/:version.Gametype/index', /* 游戏分类(game/gametype)  (ok)*/
    ':version/game/list$'                => 'api/:version.Game/index', /* 游戏列表(game/list) (ok) */
    ':version/game/detail$'              => 'api/:version.Game/read', /* 获取游戏详情信息(game/detail) (ok) */
    ':version/game/down$'                => 'api/:version.Gamedown/geturl', /* 游戏下载地址(game/down) (ok) */
    ':version/game/server/list$'         => 'api/:version.Gameserver/index', /* 游戏开服列表 ( game/server/list )  (ok)  */
    ':version/share/detail$'             => 'api/:version.Share/read', /* 游戏分享信息 ( share/detail ) */
    ':version/share/notify$'             => 'api/:version.Share/notify$', /* 游戏分享信息回调(share/notify) */
    /* 搜索 */
    ':version/search/index$'             => 'api/:version.Search/index', /* 搜索(search/index) (ok)  */
    /* 轮播图 */
    ':version/slide/list$'               => 'api/:version.Slide/index', /* 轮播图、广告(slide/list)(ok)   */
    /* 礼包 */
    ':version/gift/list$'                => 'api/:version.Gift/index', /* 礼包列表(gift/list)(ok)  */
    ':version/gift/detail$'              => 'api/:version.Gift/read', /* 礼包详细信息(gift/detail) (ok) */
    ':version/user/gift/add$'            => 'player/:version.Usergift/save', /* 【内】领取礼包(user/gift/add) (ok) */
    ':version/user/gift/list$'           => 'player/:version.Usergift/index', /* 【内】我的礼包(user/gift/list)(ok)  */
    /* 代金卷 */
    ':version/coupon/list$'              => 'api/:version.Coupon/index', /* 获取代金卷列表 ( coupon/list )(ok)  */
    ':version/coupon/detail$'            => 'api/:version.Coupon/read', /* 获取代金卷详情 ( coupon/detail )(ok)  */
    ':version/user/coupon/add$'          => 'player/:version.Usercoupon/save', /* 【内】兑换代金卷 ( user/coupon/add )(ok)  */
    ':version/user/coupon/list$'         => 'player/:version.Usercoupon/index', /* 【内】我的代金卷 ( user/coupon/list ) (ok) */
    /* 积分商城 */
    ':version/goods/list$'               => 'api/:version.Goods/index', /* 获取商品列表(goods/list)(ok)  */
    ':version/goods/detail'              => 'api/:version.Goods/read', /* 获取商品详情(goods/detail)(ok)  */
    ':version/integral/ranklist$'        => 'api/:version.Integral/ranklist', /* 获取积分排行表(integral/ranklist) (ok) */
    ':version/user/goods/add$'           => 'player/:version.Usergoods/save', /* 【内】兑换商品(user/goods/add)(ok)  */
    ':version/user/goods/list$'          => 'player/:version.Usergoods/index', /* 【内】我的商品(user/goods/list)(ok)  */
    ':version/integral/actlist$'         => 'player/:version.Useritg/actindex', /* 【内】获取积分活动(integral/actlist) */
    ':version/integral/exchange$'        => 'player/:version.Useritg/exchange', /* 【内】积分兑换(integral/exchange) */
    ':version/user/integral/get$'        => 'player/:version.Useritg/getItg', /* 【内】获取积分(user/integral/get) */
    ':version/tguser/detail$'            => 'player/:version.Useritg/tguser', /* 【内】推广员任务(tguser/detail) */
    /* 签到 */
    ':version/user/sign/list$'           => 'player/:version.Usersign/index', /* 【内】签到列表(user/sign/list) */
    ':version/user/sign/add$'            => 'player/:version.Usersign/save', /* 【内】玩家签到(user/sign/add) */
    /* 消息 */
    ':version/user/msg/list$'            => 'player/:version.Usermsg/index', /* 【内】我的消息(user/msg/list) */
    ':version/user/msg/detail$'          => 'wap/:version.Message/read', /* 【内】消息读取( user/msg/detail ) */
    ':version/user/msg/delete'           => 'player/:version.Usermsg/delete', /* 【内】消息删除( user/msg/delete ) */
    /* 充值游戏币 */
    ':version/gamemoney/charge'          => 'api/:version.Gamemoney/index', /* 【内】充值页面数据获取(gamemoney/charge) */
    ':version/gamemoney/integral/list'   => 'api/:version.Gmitg/index', /* 获取充值送积分列表( gamemoney/integral/list ) */
    ':version/gamemoney/preorder$'       => 'api/:version.Gamemoney/preorder',/* 【内】游戏币预下单(gamemoney/preorder) */
    ':version/gamemoney/pay$'            => 'api/:version.Gamemoney/pay',/*【内】游戏币充值支付(gamemoney/pay) */
    ':version/gamemoney/queryorder$'     => 'player/:version.Gamemoney/query', /* 【内】查询支付结果(gamemoney/queryorder) */
    /* 帮助中心 */
    ':version/guestbook/write$'          => 'api/:version.Guestbook/save', /* 【内】信息反馈( guestbook/write) (ok) */
    ':version/system/aboutus'            => 'api/:version.Aboutus/index', /* 关于我们( system/aboutus) */
    /* 用户相关 */
    ':version/user/detail$'              => 'player/:version.User/read', /* 【内】获取用户信息( user/detail ) */
    ':version/user/integral/getinvlist$' => 'player/:version.Useritg/invite',/*【内】邀请奖励(user/integral/getinvlist )*/
    ':version/user/game/gmlist$'         => 'player/:version.Usergame/gmindex', /* 【内】游戏币列表( user/game/gmlist ) (ok)*/
    ':version/user/passwd/find$'         => 'player/:version.User/setpwd', /* 【内】找回密码( user/passwd/find )*/
    ':version/user/passwd/update$'       => 'player/:version.User/modpwd', /* 【内】修改密码( user/passwd/update ) */
    ':version/user/phone/bind$'          => 'player/:version.User/bindmobile', /* 【内】绑定手机( user/phone/bind ) */
    ':version/user/phone/verify$'        => 'player/:version.User/verify', /* 【内】验证手机( user/phone/verify ) */
    ':version/user/address/detail$'      => 'player/:version.User/redaddress', /* 【内】读取玩家地址(user/address/detail) */
    ':version/user/address/update$'      => 'player/:version.User/setaddress', /* 【内】修改玩家地址(user/address/update)  */
    ':version/user/info/update$'         => 'player/:version.User/set', /* 【内】修改玩家信息(user/info/update)  */
    ':version/user/portrait/update$'     => 'api/:version.Picture/setPortrait', /* 玩家上传头像(user/portrait/update)  */
    ':version/user/consume/cslist$'      => 'player/:version.Userconsume/csindex', /* 消费记录(user/consume/cslist)  */
    ':version/user/recharge/rclist$'     => 'player/:version.Userrecharge/rcindex', /* 平台币充值记录(user/recharge/rclist)  */
    ':version/user/gm/rclist$'           => 'player/:version.Userrecharge/gm_recharge_list',
    ':version/user/name/check$'          => 'player/:version.User/redinfo', /* 【内】找回密码获取用户信息( user/name/check ) */
    /* 游戏币充值记录(user/recharge/rclist)  */
    ':version/user/introducer/addcode$'  => 'player/:version.User/setintroducer', /* 添加邀请码(user/recharge/rclist)  */
    ':version/user/phone/removebind$'    => 'player/:version.User/remove_bindmobile',
    /* 【内】解除绑定手机( user/phone/removebind$ ) */
    ':version/user/email/webadd$'        => 'wap/:version.Email/index',  /* web 绑定邮箱 */
    ':version/user/email/send$'          => 'player/:version.User/send_email', /* 【内】发送邮箱验证码( user/email/send ) */
    ':version/user/email/bind$'          => 'player/:version.User/bindemail', /* 【内】绑定邮箱( user/email/bind$ ) */
    ':version/user/email/removebind$'    => 'player/:version.User/remove_bindemail',
    /* 【内】解除绑定邮箱( user/email/removebind$ ) */
    /* 页面 */
    ':version/agreement/:type'           => 'wap/:version.Agreement/read', /* 获取协议页面(agreement/type/[…]) */
    ':version/weal/note'                 => 'wap/:version.Weal/note', /* 获取卡卷说明(weal/note) */
    /* 地址 */
    ':version/address/list$'             => 'api/:version.Address/read', /* 地址 */
    /* 修复工具地址 */
    ':version/system/repair$'            => 'wap/:version.Repair/geturl', /* 修复工具地址 */
    'ios/app$'                           => 'wap/:version.Ios/index', /* APP推广页面 */

    /* 资讯 */
    ':version/news/list$'                => 'api/:version.News/index', /* 资讯 */
    ':version/news/webdetail/:id$'       => 'wap/:version.News/webRead', /* 资讯详情页 */
    ':version/news/webdetail$'           => 'api/:version.News/webread', /* 资讯详情 */
    //公告
    ':version/notice/webdetail/:id'      => 'wap/:version.Notice/index',  /* 公告详情页 */
    ':version/norice/webdetail/:id'      => 'wap/:version.Notice/index',  /* 公告详情页 */
    //充值
    ':version/user/wallet/add$'          => 'wap/:version.Wallet/index',//充值游戏币
    ':version/user/ptb/add$'             => 'wap/:version.Wallet/ptb_charge',//充值平台币
];
