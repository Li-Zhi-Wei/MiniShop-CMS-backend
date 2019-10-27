<?php


namespace app\lib\exception\pay;


use LinCmsTp5\exception\BaseException;

class PayException extends BaseException
{
    public $code = 400;
    public $msg  = '微信接口异常';
    public $error_code = '70000';
}
