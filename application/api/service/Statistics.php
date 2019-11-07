<?php


namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\User as UserModel;

class Statistics
{

    public static function getOrderStatisticsByDate($params)
    {
        // 1.根据日期间距类型返回不同应用的日期格式参数
        $format = self::handleType($params['type']);
        // 2.查询出指定时间范围内的订单统计数据
        $statisticRes = OrderModel::getOrderStatisticsByDate($params, $format['mysql']);
        // 3.生成包含指定时间范围内所有日期的初始化数组
        $range = fill_date_range($params['start'], $params['end'], $format['php'], $params['type']);
        // 4.利用封装的方法处理查询结果
        $result = self::handleReturn($statisticRes, $range);
        return $result;
    }

    public static function getUserStatisticsByDate($params)
    {
        $format = self::handleType($params['type']);
        $statisticRes = UserModel::getUserStatisticsByDate($params, $format['mysql']);
        $range = fill_date_range($params['start'], $params['end'], $format['php'], $params['type']);
        $result = self::handleReturn($statisticRes, $range);
        return $result;
    }

    /**
     * 根据日期间距类型返回不同应用的日期格式化参数
     * @param $type
     * @return array
     */
    protected static function handleType($type)
    {
        $map = [
            'year' => [
                'php' => 'Y',
                'mysql' => '%Y'
            ],
            'month' => [
                'php' => 'm',
                'mysql' => '%m'
            ],
            'day' => [
                'php' => 'd',
                'mysql' => '%d'
            ],
            'hour' => [
                'php' => 'H',
                'mysql' => '%H'
            ],
            'minute' => [
                'php' => 'i',
                'mysql' => '%i'
            ],
        ];
        return $map[$type];
    }

    /**
     * 查询结果处理方法
     * @param $statisticRes array 查询结果
     * @param $range array 按日期初始化的数组
     * @return array 处理后的统计结果
     */
    protected static function handleReturn($statisticRes, $range)
    {
        // 1.如果指定时间范围内的没有统计结果，直接返回初始化数组
        if ($statisticRes->isEmpty()) return $range;
        // 2.利用内置函数array_column()得到由date字段组成的数组，用于方便后续使用
        // 函数返回的数组元素顺序和原数组一致（重点）
        $rangeColumn = array_column($range, 'date');
        // 3.把结果集转换成数组
        $statisticRes = $statisticRes->toArray();
        // 4.利用内置函数array_walk()给$statisticRes数组的每个元素作用函数
        array_walk($statisticRes, function ($item) use (&$range, $rangeColumn) {
            // 5.找出在$rangeColumn中元素值等于$statisticRes元素日期的元素，返回这个元素的key
            $key = array_search($item['date'], $rangeColumn);
            // 6.对$range指定的$key元素重新赋值，覆盖初始化数据
            $range[$key] = $item;
        });
        return $range;
    }
}
