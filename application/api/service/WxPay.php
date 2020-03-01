<?php


namespace app\api\service;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\pay\PayException;
use app\api\model\Order as OrderModel;
require_once "../extend/wx_pay/WxPay.Api.php";

class WxPay
{
    private $orderNo;
    private $config;

    public function __construct($orderNo)
    {
        $this->orderNo = $orderNo;
        $this->config = new WxPayConfig('wx');
    }

    /**
     * 获取订单状态
     */
    public function getWxOrderStatus()
    {
        // 生成查询参数对象
        $inputObj = $this->generateOrderQuery();
        // 调用微信支付订单查询接口
        try {
            $payStatus = \WxPayApi::orderQuery($this->config, $inputObj);
            if ($payStatus['result_code'] === 'FAIL') {
                throw new PayException(['msg' => '微信支付：'.$payStatus['err_code_des']]);
            }
            if ($payStatus['trade_state'] === 'SUCCESS') {
                $result = [
                    'trade_state' => '支付成功', // 交易状态
                    'trade_state_desc' => $payStatus['trade_state_desc'], // 交易状态描述
                    'out_trade_no' => $payStatus['out_trade_no'], // 商户订单号
                    'transaction_id' => $payStatus['transaction_id'], // 微信支付订单号
                    'is_subscribe' => $payStatus['is_subscribe'], // 是否关注公众账号
                    'total_fee' => ((float)$payStatus['total_fee'])/100, // 订单总金额，单位为分
                    'cash_fee' => ((float)$payStatus['cash_fee'])/100, // 现金支付金额
                    'time_end' => $payStatus['time_end'], // 支付完成时间
                    'attach' => $payStatus['attach'], // 附加数据
                ];
            } else {
                $result = [
                    'trade_state' => config('wx.trade_state')[$payStatus['trade_state']], // 交易状态
                    'trade_state_desc' => $payStatus['trade_state_desc'], // 交易状态描述
                    'out_trade_no' => $payStatus['out_trade_no'], // 商户订单号
                    'total_fee' => ((float)$payStatus['total_fee'])/100, // 订单总金额，单位为分
                ];
            }
            return $result;
        } catch (\WxPayException $ex) {
            throw new PayException(['msg' => $ex->getMessage()]);
        }
    }

    /**
     * 生成微信支付订单查询参数对象
     */
    protected function generateOrderQuery()
    {
        // 实例化订单查询输入对象
        $inputObj = new \WxPayOrderQuery();
        // 设置商户订单号，用于查询条件
        $inputObj->SetOut_trade_no($this->orderNo);
        return $inputObj;
    }

    /**
     * 订单退款
     */
    public function refund($refundFee)
    {
        try {
            // 数据库中查询订单，因为只需要知道订单的订单总金额字段，在查询时指定了要列出的字段，节省性能
            $order = OrderModel::field('total_price')->where('order_no', $this->orderNo)->find();
            // total_price通过查询数据库订单记录获得，refundFee由外部或者前端传递
            $inputObject = $this->generateRefundObject($order->total_price, $refundFee);
            $refundRes = \WxPayApi::refund($this->config, $inputObject);
            if ($refundRes['return_code'] === 'FAIL') {
                throw new PayException(['msg' => $refundRes['return_msg']]);
            }
            if ($refundRes['result_code'] === 'FAIL') {
                throw new PayException(['msg' => $refundRes['err_code_des']]);
            }
        } catch (\WxPayException $ex) {
            throw new PayException(['msg' => $ex->getMessage()]);
        }
        $order->status = OrderStatusEnum::REFUNDED;
        $order->save();
        $result = [
            'result_code' => $refundRes['return_code'],
            'out_trade_no' => $refundRes['out_trade_no'],
            'out_refund_no' => $refundRes['out_refund_no'],
            'total_fee' => $refundRes['total_fee'],
            'refund_fee' => $refundRes['refund_fee'],
        ];
        return $result;
    }

    /**
     * 生成微信支付退款提交对象
     * @param $totalFee 订单总金额
     * @param $refundFee 退款金额
     */
    protected function generateRefundObject($totalFee, $refundFee)
    {
        $inputObject = new \WxPayRefund();
        // 设置要退款的商户订单号
        $inputObject->SetOut_trade_no($this->orderNo);
        // 设置退款订单号
        // 一笔微信支付订单是可以分开多次退款的，所以需要为每次退款都生成一个订单号作为退款订单号
        // 同一个退款订单号发起多次退款，不会进行多次退款。
        // 这里调用一个我们自己实现的方法用于生成退款订单号
        $inputObject->SetOut_refund_no($this->makeOrderNo());
        // 设置订单总金额，需与原支付订单总金额一致
        // 微信支付接口接收的金额单位是分为单位，所以我们要*100把元化成分
        $inputObject->SetTotal_fee($totalFee * 100);
        // 设置本次退款金额，单位为分
        $inputObject->SetRefund_fee($refundFee * 100);
        // 设置操作人信息，默认传微信支付商户的merchanId即可
        $inputObject->SetOp_user_id($this->config->GetMerchantId());
        // 返回封装好的对象
        return $inputObject;
    }

    /**
     * 生成退款订单号
     * @return string
     */
    public function makeOrderNo()
    {
        $orderSn =
            'T' . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 退款详情查询
     */
    public function refundQuery()
    {
        try {
            $inputObject = $this->generateRefundQueryObject();
            $result = \WxPayApi::refundQuery($this->config, $inputObject);

            if ($result['return_code'] === 'FAIL') {
                throw new PayException(['msg' => $result['return_msg']]);
            }

            if ($result['result_code'] === 'FAIL') {
                throw new PayException(['msg' => $result['err_code_des']]);
            }
        } catch (\WxPayException $ex) {
            throw new PayException(['msg' => $ex->getMessage()]);
        }
        return $result;
    }

    /**
     * 生成微信支付退款详情查询参数对象
     */
    protected function generateRefundQueryObject()
    {
        $inputObject = new \WxPayRefundQuery();
        $inputObject->SetOut_trade_no($this->orderNo);
        return $inputObject;
    }

}
