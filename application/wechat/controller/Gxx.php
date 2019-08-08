<?php
/**
 * Created by PhpStorm.
 * User: WIN10
 * Date: 2019/8/8
 * Time: 15:04
 */

namespace app\wechat\controller;


use think\Controller;

class Gxx extends Controller
{
    public function index()
    {
        //获得参数 signature nonce token timestamp echostr
        if(isset($_GET['echostr'])){
            file_put_contents('55.txt','555');
            $echostr   = $_GET['echostr'];
            header('content-type:text');
            echo  $echostr;
            exit;
        }else{

            file_put_contents('66.txt','666');
            $this->reponseMsg();
        }
    }


    private function reponseMsg()
    {
        file_put_contents('77.txt','777');
    }
}