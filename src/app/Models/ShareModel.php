<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class ShareModel extends  Authenticatable
{
    //公众号转发
   public static function wx_get_jsapi_ticket() {

        $access_token =self::wx_get_token();
        $access_token= json_decode($access_token,true)['access_token'];
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
        $res =self::get_curl_contents($url);
        $res = json_decode($res, true);
        return $res['ticket'];
    }
    //获取微信公从号access_token
    public static  function wx_get_token() {
        $AppID = 'wxc650cced31474c10';//AppID(应用ID)
        $AppSecret = 'b7edbbb8c105e95688f90e592df26e0c';//AppSecret(应用密钥)
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$AppID.'&secret='.$AppSecret;
        $res =self::get_curl_contents($url);
        return $res;
    }
    //curl获取请求文本内容
  public static  function get_curl_contents($url, $method ='GET', $data = array())
  {
      if ($method == 'POST') {
          //使用crul模拟
          $ch = curl_init();
          //允许请求以文件流的形式返回
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
          curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 30);
          curl_setopt($ch, CURLOPT_URL, $url);
          $result = curl_exec($ch); //执行发送
          curl_close($ch);
      } else {
          //使用crul模拟
          $ch = curl_init();
          //允许请求以文件流的形式返回
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          //禁用https
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($ch, CURLOPT_URL, $url);
          $result = curl_exec($ch); //执行发送
          curl_close($ch);
      }
      return $result;
  }
}