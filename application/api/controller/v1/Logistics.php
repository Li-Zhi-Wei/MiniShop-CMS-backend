<?php


namespace app\api\controller\v1;

use app\api\service\Order as OrderService;

class Logistics
{
    /**
     * 查询订单物流状态
     * @param('orderNo','订单号','require|length:10,16|alphaNum')
     */
    public function getLogistics($orderNo)
    {
        $result = OrderService::queryLogistics($orderNo);
        return $result;
    }
}
