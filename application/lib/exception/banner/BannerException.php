<?php


namespace app\lib\exception\banner;


use LinCmsTp5\exception\BaseException;

class BannerException extends BaseException
{
    public $code = 400;
    public $msg = '轮播图接口异常';
    public $error_code = '70000';
}
