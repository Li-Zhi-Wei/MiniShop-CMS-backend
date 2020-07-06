<?php

use think\facade\Env;

return [
    'app_id' => '', #绑定支付的APPID（必须配置，开户邮件中可查看）
    'merchant_id' => '', #商户号（必须配置，开户邮件中可查看）
    'sign_type' => 'MD5', #签名加密类型，直接MD5即可
    'key' => '', # 微信商户平台(pay.weixin.qq.com)-->账户设置-->API安全-->密钥设置
    'cert_path' => '',
    'key_path' => '',
    'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?'.
        'grant_type=client_credential&appid=%s&secret=%s',
    'app_secret' => '',
    'trade_state' => [ // 查询订单支付状态
        'SUCCESS' => '支付成功',
        'REFUND' => '转入退款',
        'NOTPAY' => '未支付',
        'CLOSED' => '已关闭',
        'REVOKED' => '已撤销（刷卡支付）',
        'USERPAYING' => '用户支付中',
        'PAYERROR' => '支付失败(其他原因，如银行返回失败等)',
    ],
];
