<?php


namespace app\lib\exception\product;


use LinCmsTp5\exception\BaseException;

class ProductException extends BaseException
{
    public $code = 400;
    public $msg  = '商品接口异常';
    public $error_code = '70000';
}
