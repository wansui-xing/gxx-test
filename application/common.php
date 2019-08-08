<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * @param int $length 随机数长度
 * @return string
 * @desc 创建随机数
 */
use think\Wechat;
function create_noncestr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}
/**
 * @param $url
 * @param array $data
 * @param int $is_post
 * @return mixed
 */
function sub_curl($url,$data=array(),$is_post=1){
    $ch = curl_init();
    if(!$is_post)//get 请求
    {
        $url =  $url.'?'.http_build_query($data);
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    if($is_post)
    {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
    $info = curl_exec($ch);
    $code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $info;
}

function add_weixin_log($data, $data_post = '', $wechat = false) {
    $log_data['cdate'] = time ();
    $log_data['data'] = is_array ( $data ) ? serialize ( $data ) : $data;
    $log_data['data_post'] = is_array ( $data_post ) ? serialize ( $data_post ) : $data_post;
    db('weixin_log')->insert( $log_data );
}
/*
 * by Litter_7
 */
function url_query_format($data=array() , $urlencode = false){
    $string = "";
    foreach ($data as $k => $v)
    {
        if($urlencode)
        {
            $v = urlencode($v);
        }
        $string .= "{$k}={$v}&";
    }
    $string = trim($string, "&");
    return $string;
}
/*
 * 根据第三方$appid获取token凭证
 */
function get_authorizer_access_token($appid){
    if($appid){
        $wechatObj = new Wechat(config('component_appid'),config('component_appsecret'));
        return $wechatObj->getAuthorizerAccessToken($appid);
    }
    return false;
}
/*
 * 第三方token获取
 */
function get_component_access_token(){
    $wechatObj = new Wechat(config('component_appid'),config('component_appsecret'));
    return $wechatObj->getComponentAccessToken();
}
/**
 * 作用：将xml转为array
 */
function xml_to_array($xml)
{
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}

/**
 * @param $arr
 * @return string
 * @数组转为xml
 */
function array_to_xml($arr){
    $xml = "<xml>";
    foreach ($arr as $key=>$val)
    {
        if (is_numeric($val))
        {
            $xml.="<".$key.">".$val."</".$key.">";

        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
    }
    $xml.="</xml>";
    return $xml;
}
function curl_post_ssl($xml, $url, $second = 30, $sslcert, $sslkey, $rooca='', $aHeader = array())
{
    $ch = curl_init();
    //超时时间
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //这里设置代理，如果有的话
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    //cert 与 key 分别属于两个.pem文件
    curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
    curl_setopt($ch, CURLOPT_SSLCERT, $sslcert);
    curl_setopt($ch, CURLOPT_SSLKEY, $sslkey);
    if($rooca){
        curl_setopt($ch, CURLOPT_CAINFO, $rooca);
    }
    if (count($aHeader) >= 1) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
    }
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $data = curl_exec($ch);
    if ($data) {
        curl_close($ch);
        return $data;
    } else {
        $error = curl_errno($ch);
        curl_close($ch);
        return false;
    }
}

/**
 * @param $url
 * @return string
 * desc:根据appid获取授权方信息
 */
function get_info_by_appid($appid){
    if(empty($appid)){
        return false;
    }
    $map['appid'] = $appid;
    $info = db('user_account')->where($map)->find();
    if($info){
        if($info['sslcert']){
            $info['sslcert'] = ROOT_PATH.'public'.$info['sslcert'];
            $info['sslkey'] = ROOT_PATH.'public'.$info['sslkey'];
            return $info;
        }/*else{
            return json(array('errcode'=>'0','errmsg'=>'请完善商户信息并上传商户证书'));
        }*/
    }else{
        return json(array('errcode'=>'0','errmsg'=>'appid错误或未绑定到平台'));
    }
}
//获取公众号access_token
/*function get_authorizer_access_token() {

    $info = get_token_appinfo ( $token );

    // 微信开放平台一键绑定
    if ($token == 'gh_3c884a361561' || $info ['is_bind']) {
        $access_token = get_authorizer_access_token ( $info ['appid'], $info ['authorizer_refresh_token'], $update );
    } else {
        $access_token = get_access_token_by_apppid ( $info ['appid'], $info ['secret'], $update );
    }

    // 自动判断access_token是否已失效，如失效自动获取新的
    if ($update == false) {
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $access_token;
        $res = wp_file_get_contents ( $url );
        $res = json_decode ( $res, true );
        if ($res ['errcode'] == '40001') {
            $access_token = get_access_token ( $token, true );
        }
    }

    return $access_token;
}*/

// 防超时的file_get_contents改造函数
function wp_file_get_contents($url) {
    $context = stream_context_create ( array (
        'http' => array (
            'timeout' => 30
        )
    ) ); // 超时时间，单位为秒

    return file_get_contents ( $url, 0, $context );
}
function qrcode($url="http://open.max-digital.cn",$level=3,$size=4,$is_base=false)
{
    Vendor('phpqrcode.phpqrcode');
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
    //生成二维码图片
    $object = new \QRcode();
    if($is_base==true){
        ob_start();
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();
        return 'data:image/png;base64,'.$imageString;
    }else{
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);exit;
    }
}
//阿里云短信接口发送
function aliyun_sms($phone,$templateCode,$paramString,$signName='MAX智源动力'){
    $config = config('sms_ali_config');
    $app_key = $config['app_key'];//"24537034";
    $app_secret = $config['app_secret'];//"b7e78f96c164083332aa87c828945deb";
    $data = array();
    $data['ParamString'] = $paramString;
    $data['SignName'] = $signName;
    $data['TemplateCode'] = $templateCode;
    $data['RecNum'] = trim($phone);
    $request_host = "http://sms.market.alicloudapi.com";
    $request_uri = "/singleSendSms";
    $request_method = "GET";
    $info = "";
    $infos = do_get($app_key, $app_secret, $request_host, $request_uri, $request_method, $data, $info);
    $result = json_decode($infos,true);
    if($result && $result['success']){
        $return['status'] = 1;
        $return['error'] = 'OK';
    }else{
        $return['status'] = -1;
        $return['error'] = $result['message']?$result['message']:'发送失败';
    }
    return $return;
}
function aliyun_mail($email,$title,$content){
    include_once VENDOR_PATH.'aliyun-php-sdk-dm/aliyun-php-sdk-core/Config.php';
    $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", "LTAIsqhg9DAk1yOM", "TI70ZN717f31gzUzmmUx8WozNZ9DxV");
    $client = new \DefaultAcsClient($iClientProfile);
    $request = new \Dm\Request\V20151123\SingleSendMailRequest();
    $request->setAccountName("cat-acc-center@aliyun-email.max-digital.cn");
    $request->setFromAlias("SmartData");
    $request->setAddressType(1);
    //$request->setTagName("控制台创建的标签");
    $request->setReplyToAddress("true");
    $request->setToAddress($email);
    $request->setSubject($title);
    $request->setHtmlBody($content);
    try {
        $response = $client->getAcsResponse($request);
        return true;
    } catch (ClientException  $e) {
//            return json($e->getErrorCode());
        return $e->getErrorMessage();
    }
    catch (ServerException  $e) {
//            return json($e->getErrorCode());
        return $e->getErrorMessage();
    }
}
function do_get($app_key, $app_secret, $request_host, $request_uri, $request_method, $request_paras, &$info) {
    ksort($request_paras);
    $request_header_accept = "application/json;charset=utf-8";
    $content_type = "";
    $headers = array(
        'X-Ca-Key' => $app_key,
        'Accept' => $request_header_accept
    );
    ksort($headers);
    $header_str = "";
    $header_ignore_list = array('X-CA-SIGNATURE', 'X-CA-SIGNATURE-HEADERS', 'ACCEPT', 'CONTENT-MD5', 'CONTENT-TYPE', 'DATE');
    $sig_header = array();
    foreach($headers as $k => $v) {
        if(in_array(strtoupper($k), $header_ignore_list)) {
            continue;
        }
        $header_str .= $k . ':' . $v . "\n";
        array_push($sig_header, $k);
    }
    $url_str = $request_uri;
    $para_array = array();
    foreach($request_paras as $k => $v) {
        array_push($para_array, $k .'='. $v);
    }
    if(!empty($para_array)) {
        $url_str .= '?' . join('&', $para_array);
    }
    $content_md5 = "";
    $date = "";
    $sign_str = "";
    $sign_str .= $request_method ."\n";
    $sign_str .= $request_header_accept."\n";
    $sign_str .= $content_md5."\n";
    $sign_str .= "\n";
    $sign_str .= $date."\n";
    $sign_str .= $header_str;
    $sign_str .= $url_str;

    $sign = base64_encode(hash_hmac('sha256', $sign_str, $app_secret, true));
    $headers['X-Ca-Signature'] = $sign;
    $headers['X-Ca-Signature-Headers'] = join(',', $sig_header);
    $request_header = array();
    foreach($headers as $k => $v) {
        array_push($request_header, $k .': ' . $v);
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $request_host . $url_str);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    return $ret;
}
/*function qrcode($url="http://open.max-digital.cn",$level=3,$size=4)
{
    Vendor('phpqrcode.phpqrcode');
    $errorCorrectionLevel =intval($level) ;//容错级别
    $matrixPointSize = intval($size);//生成图片大小
    //生成二维码图片
    $object = new \QRcode();
    ob_start();
    $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 2);
    $imageString = base64_encode(ob_get_contents());
    ob_end_clean();
    return $imageString;
}*/


function curl_get($url, $type = 0, $params = array(), $timeout = 5)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    if($type){  //判断请求协议http或https
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    }

    $file_contents = curl_exec($ch);
    curl_close($ch);
    return json_decode($file_contents, true);
}


/**
 * @param string $url post请求地址
 * @param array $params
 * @return mixed
 */
function curl_post( $url,  $params,  $header = 'json')
{

    switch ($header) {
        case 'text':
            $data_string = http_build_query($params);
            break;
        case 'json':
            $data_string = json_encode($params,JSON_UNESCAPED_UNICODE);
            $http_header[] = 'Content-Type: application/json';
            break;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 25);//设置超时时间
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

    if (!strcmp($header, 'json')) {
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER,
            $http_header);
    }

    $data = curl_exec($ch);
    if (false === $data) {

        error('请求超时',9999);
    }
    curl_close($ch);
    return strcmp($header, 'json') ? ($data) : json_decode($data, true);
}

function success($data, $msg = 'ok')
{
    return json(['errcode'=>0,'errmsg'=>$msg,'data'=>$data]);
//     json_encode(["status" => 0, "msg" => $msg, "request_url" => \think\Request::instance()->url(), "data" => $data]);
}


function error($msg, $status = -1)
{
    die(json_encode(array('status' => $status, 'msg' => $msg)));
}

/**
 * @param int $length
 * @return null|string
 */
function getRandChar( $length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}