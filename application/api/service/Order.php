<?php


namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\DeliverRecord as DeliverRecordModel;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\order\OrderException;
use app\lib\token\Token;
use Finecho\Logistics\Logistics;
use think\Db;
use think\Exception;

class Order
{
    private $order;

    public function __construct($orderId)
    {
        // 根据传入的id查询出对应记录，拿到对应记录的模型实例
        $order = OrderModel::get($orderId);
        if (!$order) throw new OrderException(['code' => 404, 'msg' => '指定的订单不存在']);
        $this->order = $order;
    }

    /**
     * 发货(发送微信模板消息)
     * @param string $company 快递公司编码
     * @param string $number 快递单号
     * @return bool
     */
    public function deliverGoods($company, $number)
    {
        // 判断订单的状态是否是已支付或者已支付但库存不足的状态
        if ($this->order->status !== OrderStatusEnum::PAID && $this->order->status !== OrderStatusEnum::PAID_BUT_OUT_OF) {
            throw new OrderException(['msg' => '当前订单不允许发货，请检查订单状态', 'error_code' => '70008']);
        }
        // 启动事务
        Db::startTrans();
        try {
            // 创建一条发货单记录
            DeliverRecordModel::create([
                'order_no' => $this->order->order_no,
                'comp' => $company,
                'number' => $number,
                'operator' => Token::getCurrentName()
            ]);
            // 改变订单状态
            $this->order->status = OrderStatusEnum::DELIVERED;
            // 调用模型sava()方法更新记录
            $this->order->save();
            // 提交事务
            Db::commit();
            $message = new DeliveryMessage();
            $result = $message->sendDeliveryMessage($this->order,$number);
            return $result;
        } catch (Exception $ex) {
            // 回滚事务
            Db::rollback();
            throw new OrderException(['msg' => '订单发货不成功', 'error_code' => '70009']);
        }
    }

    /**
     * 查询物流信息
     */
    public static function queryLogistics($orderNo)
    {
        $deliverRecord = DeliverRecordModel::where('order_no', $orderNo)->find();
        if (!$deliverRecord) {
            throw new OrderException(['msg' => '未查询到指定订单号发货单记录', 'error_code' => 70011]);
        }
        // 查询缓存中是否有该快递单号的快递信息
        $cache = cache($deliverRecord->comp . $deliverRecord->number);
        // 如果有，直接返回缓存中的信息
        if ($cache) return $cache;
        // 如果不存在，调用第三方扩展进行快递查询
        // 获取第三方扩展需要的配置信息
        $config = config('logistics.config');
        // 获取快递编码对应公司名称
        $comp = config('logistics.comp')[$deliverRecord->comp];
        if($comp=='顺丰'||$comp=='京东'){
            throw new OrderException(['msg'=>'无法查询顺丰和京东快递','error_code' => 70012]);
        }
        // 实例化第三方扩展类并调用query查询方法，第一个参数是快递单号，第二个参数是快递公司名称(可选，但推荐传递)
        try {
            $logisticsOrder = (new Logistics($config))->query($deliverRecord->number, $comp);
            // 查询成功后把查询结果缓存起来，保留1200秒，即20分钟，这个缓存的过期时间可以按自己需要设置
            cache($deliverRecord->comp . $deliverRecord->number, $logisticsOrder['list'], 1200);
            // 返回查询结果
            return $logisticsOrder['list'];
        } catch (\Finecho\Logistics\Exceptions\Exception $ex) {
            throw new OrderException(['msg' => $ex->getMessage(), 'error_code' => 70012]);
        }
    }

}
