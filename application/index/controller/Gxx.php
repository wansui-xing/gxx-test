<?php
/**
 * Created by PhpStorm.
 * User: WIN10
 * Date: 2019/7/26
 * Time: 11:09
 */

namespace app\index\controller;


use app\index\model\user;

class Gxx
{

    public function index()
    {


        echo THINK_VERSION;die;

        $get = input('get.url');
        $preg = preg_match("/^(http:\/\/|https:\/\/).*$/", $get);

        if (!$preg) {
           echo '不合法';
        }else{
            echo '合法';
        }

        print_r($get);die;

        echo 123;die;



        $user = new user();
        $users = $user->where('id','>','0')->select();
        $users = collection($users)->toArray();
        if (count($users ) > 0){
            foreach ($users as &$u){
                $u['score']= $u['score'] +1;
                unset($u);
            }
        }
    }




    public function sort()
    {

        $arr = [
            'a'=>2,
            'b'=>3,
            'c'=>4,
            'd'=>5
        ];

        $arr2 = ['a'=>5,'b'=>6,'e'=>7];

        $result = $arr + $arr2;

        print_r($result);

        die;

        $arr = [3,1,5,11,88,22,99];

        for ($i=0; $i < count($arr); $i++){

            for ($j=0; $j < count($arr)-1; $j++){
                if($arr[$j] > $arr[$j+1]){
                    $stmp = $arr[$j+1];
                    $arr[$j+1] = $arr[$j];
                    $arr[$j] = $stmp;
                }
            }
        }


        print_r($arr);
    }

}