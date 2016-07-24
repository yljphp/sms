<?php


namespace Yljphp\Sms;

use Illuminate\Support\Facades\Config;

class Sms
{
    protected static $fileName;
    protected  static $smsObj;
    public static function init()
    {
        self::$fileName = studly_case(Config::get('sms.default')).'Sms';
        $className = 'Yljphp\\Sms\\Tools\\'.self::$fileName;
        self::$smsObj = new $className();
    }
    

    public function __call($name, $arguments)
    {
        self::init();
        return call_user_func_array([self::$smsObj,$name], $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        self::init();
        return call_user_func_array([self::$smsObj,$name], $arguments);
    }

}