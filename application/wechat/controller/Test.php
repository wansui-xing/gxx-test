<?php

namespace app\wechat\controller;

use think\cache\driver\Redis;
use think\Controller;
use think\Request;

class Test extends Controller
{

    public $redis ;

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $timestamp = $_GET['timestamp'];
        $nonce     = $_GET['nonce'];
        $token     = 'gxx';
        $signature = $_GET['signature'];
        $array     = array($timestamp,$nonce,$token);
        sort($array);
        //将排序后的三个参数拼接之后参数拼接之后进行sha1加密
        $tmpstr    = implode('',$array);
        $tmpstr    = sha1($tmpstr);
//        $this->wxLog('hello');
        trace('日志信息','info');
        trace('日志信息','error');
        //将加密后的字符串与signature进行对比；
        if($tmpstr == $signature && isset($_GET['echostr'])){
            echo $_GET['echostr'];
            exit;
        }else{
            $this->responseMsg();

        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $redis = new \Redis();
        $result = $redis->connect(config('redis.host'), config('redis.port'));
        $selectRedis =$redis->select(1);//选择数据库3
        $data  =[];
        $data['time'] = time();
//        $data['access_token'] = $accessToken;
        print_r(time());
//        $get_access_token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
//            .config('wechat.appID').'&secret='.config('wechat.appSecret');
//        $accessToken= curl_get($get_access_token,true);
//        if(isset($accessToken['access_token'])){
//            $result = $redis->set('access_token',$accessToken);
//        }
//        print_r($accessToken);die;
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
