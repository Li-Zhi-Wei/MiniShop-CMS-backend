<?php


namespace app\api\service;

use app\api\model\DeliverRecord;
use app\api\model\User;
use app\lib\exception\order\OrderException;
use app\lib\exception\user\UserException;

class DeliveryMessage extends WxMessage
{
    /**
     * 发送发货模板消息
     * 如果想添加模板消息,新建一个类,继承WxMessage,请参考本类
     */

    const DELIVERY_MSG_ID = 'isij44MNiFNUMP2Z_ku-h1eHdO2z7HqpDs08aA8_ygA';// 小程序模板消息ID号


    public function sendDeliveryMessage($order,$number)
    {
        if (!$order) {
            throw new OrderException();
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = 'pages/home/home';
        $this->prepareMessageData($order,$number);
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageData($order,$number)
    {
        $data = [
            'keyword1' => [
                'value' => $order->snap_name
            ],
            'keyword2' => [
                'value' => $order->snap_address->name
            ],
            'keyword3' => [
                'value' => $order->snap_address->province.$order->snap_address->city.$order->snap_address->country.$order->snap_address->detail
            ],
            'keyword4' => [
                'value' => $number
            ],
        ];
        $this->data = $data;
    }

    private function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }
}
