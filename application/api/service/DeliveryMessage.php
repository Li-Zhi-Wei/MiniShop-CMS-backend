<?php


namespace app\api\service;

use app\api\model\User;
use app\lib\exception\order\OrderException;
use app\lib\exception\user\UserException;

class DeliveryMessage extends WxMessage
{
    /**
     * 发送发货模板消息
     * 如果想添加模板消息,新建一个类,继承WxMessage,请参考本类
     */

    const DELIVERY_MSG_ID = 'roijKBiz7SaQriuBW54nqIFvi-upboCz_MUSziyHEsE';// 小程序订阅消息ID号


    public function sendDeliveryMessage($order,$deliverData)
    {
        if (!$order) {
            throw new OrderException();
        }
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->page = 'pages/home/home';
        $this->prepareMessageData($order,$deliverData);
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageData($order,$deliverData)
    {
        $this->data = [
            'thing5' => [ // 商品名
                'value' => $order->snap_name
            ],
            'name11' => [ // 收货人
                'value' => $order->snap_address['name']
            ],
            'phrase2' => [ // 快递公司
                'value' => config('logistics.comp')[$deliverData['comp']]
            ],
            'character_string3' => [ // 快递单号
                'value' => $deliverData['number']
            ],
        ];
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
