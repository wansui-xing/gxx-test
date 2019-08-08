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

