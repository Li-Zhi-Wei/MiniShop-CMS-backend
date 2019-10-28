<?php


namespace app\lib\exception\analysis;


use LinCmsTp5\exception\BaseException;

class AnalysisException extends BaseException
{
    public $code = 400;
    public $msg = '统计接口异常';
    public $error_code = '70000';
}
