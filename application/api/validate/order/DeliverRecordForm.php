<?php


namespace app\api\validate\order;


use LinCmsTp5\validate\BaseValidate;

class DeliverRecordForm extends BaseValidate
{
    protected $rule = [
        'page' => 'require|number',
        'count' => 'require|number|between:1,15',
        'order_no' => 'length:16|alphaNum',
        'number' => 'alphaNum',
        'operator' => 'chsAlphaNum'
    ];

    protected $message = [
        'order_no' => '订单号只能为16位字母或数字',
        'number' => '快递单号只能为字母或数字',
        'operator' => '发货人只能为汉字字母或数字'
    ];
}
