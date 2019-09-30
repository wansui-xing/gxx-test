<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


use \think\Route;


Route::get('add','index/Index/create');
Route::get('check_valid','wechat/Test/firstValid'); //  微信测试号接口配置信息 (验证当前接口是否有效)
Route::get('add','wechat/Test/create');
Route::get('del','wechat/Test/delete');
Route::get('list','wechat/Test/getUserList');
Route::get('info','wechat/Test/getUserInfo');
Route::get('qr_code','wechat/Test/qrCode');
Route::get('auth','wechat/Test/authorization');
Route::get('get_openid','wechat/Test/getOpenId');
Route::get('token','wechat/Test/pageToken');
Route::get('index','wechat/Gxx/index');
Route::get('aa','wechat/Test/test');
Route::post('msg','wechat/Test/responseMsg');


Route::get('gxx','index/Gxx/index');
Route::get('px','index/Gxx/sort');




Route::group('cron',function (){
    Route::get('add',function (){

        $data= [
            ['aa'=>1],
            ['ab'=>2],
        ];




        var_dump(json_encode($data));die;
        $a = "{'commentList': [], 'likeList': [], 'type': 4, 'snsId': '-5310572607919550341', 'createTime': 1565953667, 'momentEntity': {'content': '', 'picSize': 0, 'urls': [], 'userName': 'wxid_iui6k1nxdbun21', 'snsId': '-5310572607919550341', 'createTime': 1565953667}}";

       var_dump(json_decode($a));die;
        echo 12321312;
    });
});




Route::get('worker','');















