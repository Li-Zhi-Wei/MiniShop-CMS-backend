<?php


namespace app\api\controller\v1;


use app\api\service\WxPay;
use think\facade\Hook;
use think\facade\Request;
use app\api\model\Order as OrderModel;
use app\lib\exception\order\OrderException;
use app\api\service\Order as OrderService;
use app\api\service\WxPay as WxPayService;

class Order
{
    /**
     * 分页查询所有订单记录
     * @auth('查询订单','订单管理')
     * @validate('OrderForm')
     */
    public function getOrders()
    {
        $params = Request::get();
        $orders = OrderModel::getOrdersPaginate($params);
        if ($orders['total_nums'] === 0) {
            throw new OrderException([
                'code' => 404,
                'msg' => '未查询到相关订单',
                'error_code' => '70007'
            ]);
        }
        return $orders;
    }

    /**
     * 订单发货
     * @auth('订单发货','订单管理')
     * @param('id','订单id','require|number')
     * @param('comp','快递公司编码','require|alpha')
     * @param('number','快递单号','require|alphaNum')
     */
    public function deliverGoods($id)
    {
        $params = Request::post();
        $result = (new OrderService($id))->deliverGoods($params['comp'], $params['number']);
        return writeJson(201, $result, '发货成功');
    }

    /**
     * 查询订单支付状态
     * @auth('订单支付状态','订单管理')
     * @param $orderNo
     */
    public function getOrderPayStatus($orderNo)
    {
        $result = (new WxPayService($orderNo))->getWxOrderStatus();
        return $result;
    }

    /**
     * 订单退款
     * @auth('订单退款','财务管理')
     * @params('order_no','订单号','require')
     * @params('refund_fee','退款金额','require|float|>:0')
     */
    public function refund()
    {
        $params = Request::post();
        $result = (new WxPay($params['order_no']))->refund($params['refund_fee']);
        Hook::listen('logger', "操作订单{$params['order_no']}退款,退款金额{$params['refund_fee']}");
        return $result;
    }

    /**
     * 订单退款查询
     * @auth('查询退款详情','财务管理')
     * @param $orderNo
     * @return \成功时返回，其他抛异常
     */
    public function refundQuery($orderNo)
    {
        $result = (new WxPay($orderNo))->refundQuery();
        return $result;
    }

}
