<?php


namespace app\lib\exception\wx;


use LinCmsTp5\exception\BaseException;

class WxMessageException extends BaseException
{
    public $code = 400;
    public $msg = '微信订阅消息发送失败';
    public $errorCode = 10006;
}
