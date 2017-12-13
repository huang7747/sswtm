//tg 推广 su汇总 gp 小组

var lsspa={};
lsspa.data={};
lsspa.funcs={};

lsspa.funcs.gotable=function(appid){
    window.location.href='#/tuanzhan/'+appid;
};

$.ajaxSetup({
    async : false
});
$.get('http://www.sswtm.cn/json/tabllist.json',function(data,status){//ajax接受参数
    if(status=='success'){
    lsspa.shaWu=data.shaWu;
    lsspa.oatuig=data.oatuig;
    lsspa.tuanzha=data.tuanzha;
    lsspa.tuiguan=data.tuiguan;
    }else{
        alert('未接到参数!');
    }
});
lsspa.sidelist=[];//路由的分区
var index='';
function addSide(shenfen){
    switch (shenfen){
        case 1:
            lsspa.sidelist.push(lsspa.shaWu);
            index='/home';
            break;
        case 2:
            lsspa.sidelist.push(lsspa.oatuig,lsspa.tuanzha);
            index='/home';
            break;
        case 3:
            lsspa.sidelist.push(lsspa.tuiguan);
            index='/tgindex';
            break;
        default:
            alert('你没有权限!')
    }
}
        addSide(1);

var lsapp=angular.module('acespa',['ngRoute']);
    lsapp.controller('list',function($scope){
        $scope.sidelist=lsspa.sidelist;
    });
    lsapp.config(function($routeProvider){
        $routeProvider
            .when('/home',{
                templateUrl:'/home.html'
            })
            .when('/tt/:id',{
                templateUrl:'/public/tuanzha/commontable.html'
            })
            .when('/tuiguan/:id',{
                templateUrl:'/public/tuiguan/tuiguan.html'
            })
            .when('/tgindex',{
                templateUrl:'/public/tuiguan/tgindex.html'
            })
            .when('/requestgame',{
                templateUrl:'/public/shawu/requestgame.html'
            })
            .when('/edit',{
                templateUrl:'/sdk/Game/edit.html'
            })
            .when('/del',{
                templateUrl:'/sdk/Game/delindex.html'
            })
            .otherwise({
                redirectTo:'/'
            });
        $routeProvider
            .when('/',{
                redirectTo:index
            })
});
