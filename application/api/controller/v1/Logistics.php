<?php


namespace app\api\controller\v1;

use app\api\service\Order as OrderService;

class Logistics
{
    /**
     * 查询订单物流状态
     * @auth('查询订单','订单管理')
     * @param('orderNo','订单号','require|length:10,16|alphaNum')
     */
    public function getLogistics($orderNo)
    {
        $result = OrderService::queryLogistics($orderNo);
        return $result;
    }
}
