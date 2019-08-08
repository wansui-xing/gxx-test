<?php
/**
 * Created by PhpStorm.
 * User: WIN10
 * Date: 2019/7/30
 * Time: 11:37
 */

namespace app\library;


class weChat
{

    protected $access_token;

    /**
     * 获取access_token,
     * wechat constructor.
     */
    public function __construct()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.config('weChat.appID').'&secret='.config('weChat.appSecret');
        $redis = pRedis::getInstance();

        $getRedisAccessToken = $redis->get('access_token');
        if ($getRedisAccessToken){
            if((time() - $getRedisAccessToken['time']) > 5400){
                $weChatAccessToken = curl_get($url);
                $data = [];
                $data['time'] = time();
                $data['access_token'] = $weChatAccessToken['access_token'];
                $redis->set('access_token',$data);
                $this->access_token =$weChatAccessToken['access_token'];
            }else{
                $this->access_token =$getRedisAccessToken['access_token'];
            }
        }else{
            $weChatAccessToken = curl_get($url);
            $data = [];
            $data['time'] = time();
            $data['access_token'] = $weChatAccessToken['access_token'];
            $redis->set('access_token',$data);
            $this->access_token =$weChatAccessToken['access_token'];
        }

    }


    public function setMenu($access_token,$data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $menuResult = curl_post($url,$data);
        print_r($menuResult);die;
    }

    public function qrCode()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
        $data = [
            'expire_seconds'=>604800,
            'action_name'=>'QR_STR_SCENE',
            'action_info'=>[
                'scene'=>[
                    'scene_id'=>1
                ]
            ]
        ];
        $qrCode= curl_post($url,$data);
        print_r($qrCode);die;
        $getQrCode ='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrCode['ticket'];

        print_r($qrCodeResult);die;
    }



}