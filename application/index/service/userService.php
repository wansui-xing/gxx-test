<?php
/**
 * Created by PhpStorm.
 * User: WIN10
 * Date: 2019/8/28
 * Time: 16:01
 */

namespace app\index\service;


use app\index\model\userModel;

class userService
{
    public  $user;

    public function __construct(userModel $userModel)
    {
        $this->user = $userModel;
    }

    public function saveAll()
    {
        $data = [
            [
                'name'=>'gxx',
                'mobile'=>'18616946803',
                'score'=>'0'
            ]
        ];
        $result = $this->user->saveAll();

        print_r($result);die;
    }


}