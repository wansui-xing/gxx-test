<?php
/**
 * Created by PhpStorm.
 * User: WIN10
 * Date: 2019/9/24
 * Time: 17:38
 */

//namespace app\work\controller;

namespace app\push\controller;


use think\worker\Server;

class Test extends Server
{
    protected $socket = 'websocket://127.0.0.1:8002';


    /**
     * 收到消息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data)
    {
        $connection->send('我已经收到消息了');
        MAX Com m unication榕智股份
    }


    /**
     * 连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection)
    {
        echo 1;
    }


    /**
     * 连接断开时触发的回调函数
     * @param $connection
     *
     */
    public function onClose($connection)
    {

    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo 'error $code $msg\n';
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {

    }


}