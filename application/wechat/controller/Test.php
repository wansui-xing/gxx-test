<?php

namespace app\wechat\controller;

use app\library\pRedis;
use app\library\weChat;
use think\cache\driver\Redis;
use think\Controller;
use think\Request;

class Test extends Controller
{
    public $request;
    protected $redis;
    protected $access_token;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->redis = pRedis::getInstance();
        $this->access_token = $this->redis->get('access_token')['access_token'];
    }

    /**
     * 验证签名
     */
    public function checkSignature()
    {

        $nonce     = $_GET['nonce'];
        $timestamp = $_GET['timestamp'];
        $token     = 'gxx';
        $signature = $_GET['signature'];
        $array     = array($timestamp,$nonce,$token);
        sort($array);
        //将排序后的三个参数拼接之后参数拼接之后进行sha1加密
        $tmpstr    = implode('',$array);
        $tmpstr    = sha1($tmpstr);
        trace('日志信息','info');
        trace('日志信息','error');
        if($tmpstr == $signature && isset($_GET['echostr'])){
           echo  $_GET['echostr'];
        }else{
            return false;
        }
    }

    public function firstValid()
    {
        file_put_contents('test.txt','g');
        if(isset($_GET['echostr'])){
            $echostr   = $_GET['echostr'];
            header('content-type:text');
            file_put_contents('gxx.txt','g1');
            echo  $echostr;
            exit;
        }else{
            file_put_contents('gq.txt','g2');
            $this->responseMsg();
        }


        file_put_contents('bb.txt','12');
//        $check = $this->checkSignature();
//        if(!$check){
//            file_put_contents('bb.txt','333');
//            echo $_GET['echostr'];
//            exit;
//        }else{
//            file_put_contents('bb.txt','2222');
//            $this->responseMsg();
//        }
    }

    /**
     *  创建公众号自定义菜单
     *
     * @return \think\Response
     */
    public function create()
    {
        $redis = pRedis::getInstance();
        $wechat  = new weChat();
        $access_token = $redis->get('access_token')['access_token'];

        $params = [
            'button'=>[
                [
                    'type'=>"click",
                    'name'=>"今日歌曲",
                    'key'=>"V1001_TODAY_MUSIC"
                ],
                [
                    'name'=>'菜单',
                    'sub_button'=>[
                            [
                                'type'=>'view',
                                'name'=>'搜索',
                                'url'=>'http://www.baidu.com/'
                            ],
                            [
                                'type'=>'click',
                                'name'=>'赞我不',
                                'key'=>'V1001_GOOD'
                            ],
                    ],

                ],
                [
                    'name'=>'扫码',
                    'sub_button'=>[
                        [
                            'type'=>'scancode_push',
                            'name'=>'扫码推事件',
                            'key'=>'reslmenu_0_1',
                            'sub_button'=> [ ]
                        ]
                    ]
                ]
            ],
        ];
        $result = $wechat->setMenu($access_token,$params);
        print_r($result);die;
    }



    /**
     * 删除指定资源
     *
     */
    public function delete()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->access_token;
        $result = curl_get($url);
        print_r($result);die;
    }

    /**
     * 获取关注公众号的关注者列表一次最多10000个
     */
    public function getUserList()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->access_token.'&next_openid='.'';
        $lists = curl_get($url);
        print_r($lists);die;
    }

    /**
     * 获取单个用户的基本信息
     */
    public function getUserInfo()
    {
        $openId = 'ontCus4ZoFRbys8Gvd-lVOTwPAYE';
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openId.'&lang=zh_CN';
        $info =  curl_get($url);
        print_r($info);die;
    }

    /**
     * 生成带参数的二维码
     */
    public function qrCode()
    {
        $weChat = new weChat();

       $weChat->qrCode();

    }



    public function responseMsg()
    {
//        $postxml = file_get_contents('php://input');
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
//        $postStr = file_put_contents('weChat.txt','asdasdaldhakdak看见啊哈哈地方看见啊回复');
//        file_put_contents('nnnn.txt', 123);
//        return true;
//        libxml_disable_entity_loader(true);
//
//        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
//
//        $fromUsername = $postObj->FromUserName; // 用户id
//
//        $toUsername = $postObj->ToUserName;     // 商户id
//
//        $keyword = trim($postObj->Content);     // 内容
//        $time = time();
//        $textTpl = '<xml>
//<ToUserName><![CDATA['.$fromUsername.']]></ToUserName>
//<FromUserName><![CDATA['.$toUsername.']]></FromUserName>
//<CreateTime>'.$time.'</CreateTime>
//<MsgType><![CDATA[text]]></MsgType>
//<Content><![CDATA[你好,两只黄鹂鸣翠柳]]></Content>
//</xml>';
//
//        $msgType = "text";
//        $contentStr = "Welcome to wechat world!";
//        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//        echo $resultStr;
//        die;
//        if(!empty( $keyword ))
//        {
//            $msgType = "text";
//            $contentStr = "Welcome to wechat world!";
//            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//            echo $resultStr;
//        }else{
//            echo "Input something...";
//        }
//
//        echo $textTpl;
    }

    public function test()
    {
        $result = file_put_contents('aa.txt','1231萨达所大所多阿萨德');
        print_r($result);die;
    }



    public function authorization()
    {

        $appid= config('weChat.appID');
        $redirect_uri  ='http://www.qi5233.cn/get_openid';
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
        Header("Location: $url");
    }

    public function getOpenId()
    {
        $aa = file_put_contents('ccc.txt',$_GET);
        $redis = pRedis::getInstance();
        $redis->set('weChat_code',$_GET['code']);
        $this->pageToken($_GET['code']);

    }

    public function pageToken($code)
    {
        $APPID =config('weChat.appID');
        $secret =config('weChat.appSecret');
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APPID&secret=$secret&code=$code&grant_type=authorization_code";
        $page_token = curl_post($url,[]) ;
        file_put_contents('token.txt',$page_token);
        $redis = pRedis::getInstance();
        $redis->set('token',$page_token);

        $acces_token = $page_token['access_token'];
        $openid = $page_token['openid'];
        $userinfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=$acces_token&openid=$openid&lang=zh_CN";

        $user_info = curl_post($userinfoUrl,[]);
        $redis->set('user',$user_info);
        print_r($user_info) ;

    }


}
