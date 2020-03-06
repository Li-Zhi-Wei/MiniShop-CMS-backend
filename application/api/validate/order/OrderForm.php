<?php


namespace app\api\validate\order;


use LinCmsTp5\validate\BaseValidate;

class OrderForm extends BaseValidate
{
    protected $rule = [
        'page' => 'require|number',
        'count' => 'require|number|between:1,15',
        'start|开始时间' => 'require|date',
        'end|结束时间' => 'require|date',
        'name|收货人姓名' => 'chs',
        'order_no|商品订单号' => 'alphaNum'
    ];
}
