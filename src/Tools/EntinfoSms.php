<?php

namespace Yljphp\Sms\Tools;

use Illuminate\Support\Facades\Config;

class EntinfoSms extends BaseSms
{
    protected $baseUrl;
    protected $config;

    public function __construct()
    {
        $this->config   = Config::get('sms.connections.entinfo');
        $this->baseUrl  = 'http://sdk2.entinfo.cn/webservice.asmx/';
    }


    /**
     * 发送验证码
     *
     * @param $mobile
     * @param $content
     * @param $date
     * @return bool
     */
    public function send($mobile,$content,$date = '')
    {
        $arr['mobile'] = $mobile;
        $arr['content'] = $content;
        $arr['stime'] = $date;
        return self::handelSend($arr);
    }

    /**
     * 查询余额
     *
     * @return bool
     */
    public function getBalance()
    {
        $arg['sn'] = $this->config['sn'];
        $arg['pwd'] = $this->config['pwd'];
        return self::handelRes('GetBalance',$arg);
    }


    /**
     * 处理短信发送请求
     *
     * @param $arr
     * @return bool
     */
    private function handelSend($arr)
    {
        $rrid = $arr['mobile'].mt_rand(1000000,9999999);
        $data = [
            'sn'=>$this->config['sn'],
            'pwd'=>strtoupper(md5($this->config['sn'].$this->config['pwd'])),
            'mobile'=>$arr['mobile'],
            'content'=>urlencode( $arr['content'] ),
            'ext'=>'',
            'rrid'=> $rrid,
            'stime'=> $arr['stime']
        ];

        $res = self::handelRes('mdSmsSend_u',$data);
        if($res == $rrid){
            return true;
        }
        return false;
    }

    /**
     * 处理返回字符串
     *
     * @param array $data
     * @return bool
     */
    private function handelRes($type,array $data)
    {
        $params = http_build_query($data);
        $url = $this->baseUrl.$type;
        $res = sms_curl_post($url,$params);
        preg_match('/<string xmlns=\"http:\/\/tempuri.org\/\">(.*)<\/string>/',$res,$tmp);
        if (count($tmp) > 1){
            return trim($tmp[1]);
        }
        return false;
    }
}