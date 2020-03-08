<?php


namespace app\api\service;


use app\lib\exception\wx\WxMessageException;

class WxMessage
{
    private $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=%s";
    private $touser; // 用户openid
    protected $tplID; // 订阅消息模板ID
    protected $page; // 点击模板卡片后的跳转页面
    protected $data; // 模板内容

    function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();
        $this->sendUrl = sprintf($this->sendUrl, $token);
    }

    // 开发工具中拉起的微信支付prepay_id是无效的，需要在真机上拉起支付
    protected function sendMessage($openID)
    {
        $data = [
            'touser' => $openID,
            'template_id' => $this->tplID,
            'page' => $this->page,
            'data' => $this->data,
        ];
        $result = curl_post($this->sendUrl, $data);
        $result = json_decode($result, true);
        if ($result['errcode'] == 0) {
            return '发货通知发送成功';
        } else {
            return '发货通知发送失败';
        }
    }

}
