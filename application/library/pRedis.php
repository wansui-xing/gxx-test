<?php
/**
 * Created by PhpStorm.
 * User: WIN10
 * Date: 2019/7/30
 * Time: 11:41
 */

namespace app\library;


use think\cache\driver\Redis;

class pRedis
{
    public  $redis;
    private static $_instance = null;

    public static function getInstance()
    {
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect(config('redis.host'), config('redis.port'));
        $this->redis->auth(config('redis.pwd'));
        $this->redis->select(0);//选择数据库1
    }


    /**
     * @param $key
     * @param $value
     * @param int $time
     * @return bool|string
     *
     *  字符串存储
     */
    public function set($key,$value,$time = 0)
    {
        if(!$key){
            return 'redis key不可为空';
        }
        if(is_array($value)){
            $value = json_encode($value);
        }
        if(!$time){
            return $this->redis->set($key,$value);
        }
        return $this->redis->setex($key,$value,$time);
    }

    public function get($key)
    {
        return  json_decode($this->redis->get($key),true);
    }
}