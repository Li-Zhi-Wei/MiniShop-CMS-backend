<?php


namespace app\lib\exception\order;


use LinCmsTp5\exception\BaseException;

class OrderException extends BaseException
{
    public $code = 400;
    public $msg  = '订单接口异常';
    public $error_code = '70000';
}
