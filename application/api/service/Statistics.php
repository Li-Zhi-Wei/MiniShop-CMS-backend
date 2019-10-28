<?php


namespace app\api\service;

use app\api\model\Order as OrderModel;

class Statistics
{
    /**
     * @param $params
     */
    public static function getOrderStatisticsByDate($params)
    {
        // 1.根据日期间距类型返回不同应用的日期格式参数
        $format = self::handleType($params['type']);
        // 2.查询出指定时间范围内的订单统计数据
        $statisticRes = OrderModel::getOrderStatisticsByDate($params, $format['mysql']);
        // 3.生成包含指定时间范围内所有日期的初始化数组
        $range = fill_date_range($params['start'], $params['end'], $format['php'], $params['type']);
        // 4.如果指定时间范围内的没有订单数据，直接返回初始化数组
        if ($statisticRes->isEmpty()) return $range;
        // 5.利用内置函数array_column()得到由date字段组成的数组，用于方便后续使用
        // 函数返回的数组元素顺序和原数组一致（重点）
        $rangeColumn = array_column($range, 'date');
        // 6.模型方法返回的结果是一个数据集，需要先把结果集转换成数组
        $statisticRes = $statisticRes->toArray();
        // 7.利用内置函数array_walk()给$statisticRes数组的每个元素作用一个函数
        array_walk($statisticRes, function ($item) use (&$range, $rangeColumn) {
            // 8.找出在$rangeColumn中元素值等于$statisticRes元素日期的元素，返回这个元素的key
            $key = array_search($item['date'], $rangeColumn);
            // 9.对$range指定的$key元素重新赋值，覆盖初始化数据
            $range[$key] = $item;
        });
        return $range;
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
}
