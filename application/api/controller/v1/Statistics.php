<?php


namespace app\api\controller\v1;


use think\facade\Request;
use app\api\service\Statistics as StatisticsService;
use app\lib\exception\analysis\AnalysisException;

class Statistics
{
    /**
     * 指定时间范围统计订单基础数据
     * @auth('订单数据','统计数据')
     * @param('start','开始时间','require|date')
     * @param('end','结束时间','require|date')
     * @param('type','日期间距类型','require')
     */
    public function getOrderBaseStatistics()
    {
        $params = Request::get();
        $result = StatisticsService::getOrderStatisticsByDate($params);
        // 由于返回的结果不是数据集了，不能再使用数据集的内置方法isEmpty()来判空
        if (empty($result)) throw new AnalysisException();
        return $result;
    }

    /**
     * 获取会员数据基础统计
     * @auth('会员数据','统计数据')
     * @param('start','开始时间','require|date')
     * @param('end','结束时间','require|date')
     * @return array
     */
    public function getUserBaseStatistics()
    {
        $params = Request::get();
        $result = StatisticsService::getUserStatisticsByDate($params);
        return $result;
    }

}
