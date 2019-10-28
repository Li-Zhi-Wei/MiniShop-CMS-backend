<?php


namespace app\lib\exception\user;


use LinCmsTp5\exception\BaseException;

class UserException extends BaseException
{
    public $code = 400;
    public $msg  = '会员接口异常';
    public $error_code = '70000';
}
