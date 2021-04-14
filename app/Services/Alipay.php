<?php

namespace App\Services;

use App\Services\Aop\AopClient;
use App\Services\Aop\request\AlipayTradeAppPayRequest;
use App\Services\Aop\request\AlipayTradeWapPayRequest;

class Alipay{

    protected $appId = '2021002120640514';
    protected $rsaPrivateKey = 'MIIEowIBAAKCAQEAqNdl2IMuW13ceFbw48GqDDRfWcOayYB3jh4oOuE8B/EinKZBnJ6U738zt7eVwxvpYn9BjrDC9BDzhCG+pph0lHDDw/dv29MrZADtNJw0g9ydNdQ/hhdTVjPo+DTEAB4L9UCy1FSMidHjtBjhZa3Y7GzhaY1ZgPG3N0FGeCZiPuULmBKSQ92keuSjUaXp2y9rWGr0xI8LfJRngBRk0qRvZRbt/rCeRGMlraP1NGdwUjkD/EGWa2PBFhorEhiklVaGE4/0kvZLJYQ86+f3+I4l58lShhPgjVjcVHE+2N1Kj/cLoPzBTTvJYY9HxGYGQdVJlzc7z0xvm9nMz1Jxe07ZKQIDAQABAoIBADn6pa2g9oVHiRMF//imI2fHgErJ4dh6NhPmhTga6ktr4c/bQ8KtrkVD8BWRhGsYpZ8+RdvjFT9MpuLj151L0xS6WG9uA8qADHFt5Abh8SKq2o2Gkj3QurxqXQEG1ZuYtdCFZCOH2S7mlYPd22DYPxqJFSry8B5YYusljjZqL+ianLbgAxQB/oHsfGxwU2lS75J53ZR0Rrl1KD9fbROp6elefG1LRWMcPFqdohRXwkzhD6NrTVULrrvW0Qu19TPXpFkQYpzqrRqsVK7kihvPAGtSVEFDq53x7GKgPCUVbPfmry2RJzri8BXg8OBl6fhoWq4P93zPLS//fLcsb7mZknUCgYEA7e/SsR4w0ZUU4RvgcaSyf1UlNnPNQNrawUNCfcKD2CO3QfH0Ce/q+vYnuQ3J8GSowr0Ql7+qk25TWEpVahWH17JWOB/zzBkklAJvmYyWpvZsPhpwjkpxs1xZSjuBhsw8gIlIST3pahd1c3TWSxcx+wiOQ0zxlN25+8M+vY/zMv8CgYEAtai95SDW/GsVhOMNq4Tql4YsgEhXSHTW/U8gXSvTfoW5G0pzZ/Nltf86qP1QRA2DMLC9ZBasXGbXKNbnLN+8kdHjmFRT0VU6KbYeqeGV6pgyJJywW9URm9SEIBlATeP4WBsaNia1FkMvN7QwdY7ILE045UDh/hJ8oWlXRd/x+9cCgYBmbtavtaWitKG2f7/SbOsDcm6A0L3Oa+m9Re18Ip+MD2Q3mahMFuN8gzh6rHsBaPRWUfqwuaz/p4FuAyJed0JyE66WnvrgJPrgVWQiIKpC2teirNNEDryAUQOHt7J9i7OISpG6JlM0f0DrIaOX1DaKMha81oUyZ3pH7rg196DllQKBgQCpSdF9f7cZkGmDZ1zL5JgCESXTfpbojQn1EhIlAbycgd3ZEu6thuPYUTvHVBnC8Zy6eVpltcN2cNg91Nemt/IxKhohUSSzRetoB7JXukRNVPwVpnerfMpmIAvpJd8JN1OuN8Obh0LmtkGAdclbJG5i6qwE6QwyC+RdP5/3HBWUDQKBgFl0EmwfJpJKq1c4BDrNnNT9PncnTNTwhrGmaeDiGOjQC09T0tR1qnBn/hrPJf32NFq/5At1qjPRHAyTgWzJNJ6v3qqxbhwcPhk2jxfMZNNsYgrGWKYlh0iLL0Dfcb04gOml6TnsyIPcH8cAh3GZcB+C4tAPdp1yq7hsgN7skEFE';
    protected $alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqNdl2IMuW13ceFbw48GqDDRfWcOayYB3jh4oOuE8B/EinKZBnJ6U738zt7eVwxvpYn9BjrDC9BDzhCG+pph0lHDDw/dv29MrZADtNJw0g9ydNdQ/hhdTVjPo+DTEAB4L9UCy1FSMidHjtBjhZa3Y7GzhaY1ZgPG3N0FGeCZiPuULmBKSQ92keuSjUaXp2y9rWGr0xI8LfJRngBRk0qRvZRbt/rCeRGMlraP1NGdwUjkD/EGWa2PBFhorEhiklVaGE4/0kvZLJYQ86+f3+I4l58lShhPgjVjcVHE+2N1Kj/cLoPzBTTvJYY9HxGYGQdVJlzc7z0xvm9nMz1Jxe07ZKQIDAQAB';
    protected $notifyUrl = 'http://zhonghui.xinqianhui.cn/api/notify/notify';


    /**
     * APP支付
     * @param array $params
     * @return string
     */
    public function appPay($params = [])
    {
        $biz_content = array(
            'body'                  =>  $params['body'],
            'subject'               =>  $params['subject'],
            'out_trade_no'          =>  $params['order_type'].$params['out_trade_no'],  //order_type是订单类型，用以区分设备订单、区域代理订单等
            'timeout_express'       =>  '30m',
            'total_amount'          =>  $params['total_amount'],
        );

        // 生成签名参数+签名
        $aop = new AopClient();

        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->appId;
        $aop->rsaPrivateKey = $this->rsaPrivateKey;
        $aop->alipayrsaPublicKey = $this->alipayrsaPublicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';

        $request = new AlipayTradeAppPayRequest();
        $request->setNotifyUrl($this->notifyUrl);
        $request->setBizContent("{" .
            "\"timeout_express\":\"90m\"," .
            "\"total_amount\":\"".$biz_content['total_amount']."\"," .
            "\"product_code\":\"QUICK_MSECURITY_PAY\"," .
            "\"body\":\"".$biz_content['body']."\"," .
            "\"subject\":\"".$biz_content['subject']."\"," .
            "\"out_trade_no\":\"".$biz_content['out_trade_no']."\"," .
            "  }");
        $result = $aop->sdkExecute($request);

        return $result;
    }

    /**
     * H5支付
     * @param array $params
     * @return Aop\构建好的、签名后的最终跳转URL（GET）或String形式的form（POST）
     */
    public function h5Pay($params = [])
    {
        $biz_content = array(
            'body'                  =>  $params['body'],
            'subject'               =>  $params['subject'],
            'out_trade_no'          =>  $params['order_type'].$params['out_trade_no'],  //order_type是订单类型，用以区分设备订单、区域代理订单等
            'timeout_express'       =>  '30m',
            'total_amount'          =>  $params['total_amount'],
        );

        // 生成签名参数+签名
        $aop = new AopClient();

        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->appId;
        $aop->rsaPrivateKey = $this->rsaPrivateKey;
        $aop->alipayrsaPublicKey = $this->alipayrsaPublicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';

        $request = new AlipayTradeWapPayRequest();
        $request->setNotifyUrl($this->notifyUrl);
        $request->setReturnUrl('');
        $request->setBizContent("{" .
            "\"timeout_express\":\"90m\"," .
            "\"total_amount\":\"".$biz_content['total_amount']."\"," .
            "\"product_code\":\"QUICK_WAP_WAY\"," .
            "\"body\":\"".$biz_content['body']."\"," .
            "\"subject\":\"".$biz_content['subject']."\"," .
            "\"out_trade_no\":\"".$biz_content['out_trade_no']."\"," .
            "  }");
        $result = $aop->pageExecute($request,'post');

        return $result;

    }
}

