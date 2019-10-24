<?php


namespace app\lib\exception\theme;


use LinCmsTp5\exception\BaseException;

class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '主题不存在';
    public $error_code = 70002;
}
