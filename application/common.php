<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\lib\auth\AuthMap;
use LinCmsTp5\exception\ParameterException;
use think\facade\Request;

/**
 * @param $code
 * @param $errorCode
 * @param $data
 * @param $msg
 * @return \think\response\Json
 */

function writeJson($code, $data, $msg = 'ok', $errorCode = 0)
{
    $data = [
        'error_code' => $errorCode,
        'result' => $data,
        'msg' => $msg
    ];
    return json($data, $code);
}

function rand_char($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

function split_modules($auths, $key = 'module')
{
    if (empty($auths)) {
        return [];
    }

    $items = [];
    $result = [];

    foreach ($auths as $key => $value) {
        if (isset($items[$value['module']])) {
            $items[$value['module']][] = $value;
        } else {
            $items[$value['module']] = [$value];
        }
    }
    foreach ($items as $key => $value) {
        $item = [
            $key => $value
        ];
        array_push($result, $item);
    }
    return $result;

}

/**
 * @param $auth
 * @return array
 * @throws ReflectionException
 */
function findAuthModule($auth)
{
    $authMap = (new AuthMap())->run();
    foreach ($authMap as $key => $value) {
        foreach ($value as $k => $v) {
            if ($auth === $k) {
                return [
                    'auth' => $k,
                    'module' => $key
                ];
            }
        }
    }
}

///**
// * @param string $message
// * @param string $uid
// * @param string $nickname
// * @throws \app\lib\exception\token\TokenException
// * @throws \think\Exception
// */
//function logger(string $message, $uid = '', $nickname = '')
//{
//    if ($message === '') {
//        throw new LoggerException([
//            'msg' => '日志信息不能为空'
//        ]);
//    }
//
//    $params = [
//        'message' => $nickname ? $nickname . $message : Token::getCurrentName() . $message,
//        'user_id' => $uid ? $uid : Token::getCurrentUID(),
//        'user_name' => $nickname ? $nickname : Token::getCurrentName(),
//        'status_code' => Response::getCode(),
//        'method' => Request::method(),
//        'path' => Request::path(),
//        'authority' => ''
//    ];
//    LinLog::create($params);
//}

/**
 * @return array
 * @throws ParameterException
 */
function paginate()
{
    $count = intval(Request::get('count'));
    $start = intval(Request::get('page'));

    $count = $count >= 15 ? 15 : $count;

    $start = $start * $count;

    if ($start < 0 || $count < 0) throw new ParameterException();

    return [$start, $count];
}

/**
 * @param $queryStart string 查询条件中的开始时间
 * @param $queryEnd string 查询条件中的结束时间
 * @param $format string 日期输出格式
 * @param $stepType string 日期间距类型
 * @param int $step int 日期间距
 * @return array
 */
function fill_date_range($queryStart, $queryEnd, $format, $stepType, $extend = '',$step = 1)
{
    // 定义个空数组用于接收数组元素
    $range = [];
    // 将查询条件格式化为时间戳方便后续使用
    // 区间开始日期
    $rangeStart = strtotime($queryStart);
    // 区间结束日期
    $rangeEnd = strtotime($queryEnd);
    // 循环生成数组元素
    while ($rangeStart <= $rangeEnd) {
        // 利用PHP内置函数date()按$format参数格式化时间戳
        $formattedDate = date($format, $rangeStart);
        // 初始化数据赋值，每个一日期就是一个数组元素
        $item = [
            'date' => $formattedDate,
            'count' => 0,
        ];
        // 如果存在扩展字段，给数组追加一个元素
        if ($extend) $item[$extend] = 0;
        // 将元素追加到数组中
        array_push($range, $item);
        // 利用PHP内置函数strtotime()拿到指向下一个日期的时间戳
        $rangeStart = strtotime("+{$step} {$stepType}", $rangeStart);
    }
    // 返回包含查询条件开始到结束日期之前的所有日期元素，包含初始化数据
    return $range;
}

/**
 * 发送http请求
 * @param $url
 * @param int $httpCode
 * @return bool|string
 */
function curl_get($url,&$httpCode = 0){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    //不做证书校验，部署在linux环境下请改为true
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * 发送http请求
 * @param $url
 * @param array $params
 * @return bool|string
 */
function curl_post($url, array $params = array())
{
    $data_string = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}
