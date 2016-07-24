<?php

namespace Yljphp\Sms\Tools;


abstract class BaseSms
{

    abstract public function send($mobile,$content,$date = '');
    abstract public function getBalance();
}