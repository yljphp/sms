<?php


namespace Yljphp\Sms;

use Illuminate\Support\Facades\Config;

class Sms
{
    protected $fileName;
    protected  static $smsObj;
    public function __construct()
    {
        $this->fileName = studly_case(Config::get('sms.default')).'Sms';
        $className = 'Yljphp\\Sms\\Tools\\'.$this->fileName;
        self::$smsObj = new $className();
    }
    

    public function __call($name, $arguments)
    {
        return call_user_func_array([self::$smsObj,$name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::$smsObj,$name], $arguments);
    }

}