<?php


namespace app\api\service;



use think\facade\Config;

require_once "../extend/wx_pay/WxPay.Config.Interface.php";

class WxPayConfig extends \WxPayConfigInterface
{

    private $wxConfig;

    public function __construct($config)
    {
        $this->wxConfig = Config::pull($config);
    }


    public function GetAppId()
    {
        return $this->wxConfig['app_id'];
    }

    public function GetMerchantId()
    {
        return $this->wxConfig['merchant_id'];
    }

    /**
     * TODO:支付回调url
     * 签名和验证签名方式， 支持md5和sha256方式
     **/
    public function GetNotifyUrl()
    {
        // TODO: Implement GetNotifyUrl() method.
    }

    public function GetSignType()
    {
        return $this->wxConfig['sign_type'];
    }

    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    public function GetProxy(&$proxyHost, &$proxyPort)
    {
        // TODO: Implement GetProxy() method.
    }

    /**
     * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
     * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
     * 开启错误上报。
     * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
     * @var int
     */
    public function GetReportLevenl()
    {
        // TODO: Implement GetReportLevenl() method.
    }

    public function GetKey()
    {
        return $this->wxConfig['key'];
    }

    public function GetAppSecret()
    {
        // TODO: Implement GetAppSecret() method.
    }

    /**
     * TODO：设置商户证书路径
     * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
     * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
     * 注意:
     * 1.证书文件不能放在web服务器虚拟目录，应放在有访问权限控制的目录中，防止被他人下载；
     * 2.建议将证书文件名改为复杂且不容易猜测的文件名；
     * 3.商户服务器要做好病毒和木马防护工作，不被非法侵入者窃取证书文件。
     * @var path
     */
    public function GetSSLCertPath(&$sslCertPath, &$sslKeyPath)
    {
        $sslCertPath = $this->wxConfig['cert_path'];
        $sslKeyPath = $this->wxConfig['key_path'];
    }
}
