<?php


namespace app\lib\exception\category;


use LinCmsTp5\exception\BaseException;

class CategoryException extends BaseException
{
    public $code = 400;
    public $msg  = '商品分类接口异常';
    public $error_code = '70000';
}
