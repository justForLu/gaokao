<?php
namespace App\Services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class SmsService
{

    protected $accessKeyId = 'LTAIazVTGQIOJYyE';
    protected $assessSecret = 'aIxNk5qP1McpfyI4FUza8bX6DFYEYh';
    protected $smsSign;
    protected $templateCode;

    /**
     * 发送验证码
     * @param string $mobile
     * @param array $templateParam
     * @return \AlibabaCloud\Client\Result\Result
     * @throws ClientException
     */
    public function sendCode($mobile = '',$templateParam = [])
    {
        $this->smsSign = '路传军';
        $this->templateCode = 'SMS_123674114';
        $templateParam = json_encode($templateParam);   //转为json字符串

        AlibabaCloud::accessKeyClient($this->accessKeyId, $this->assessSecret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $mobile,
                        'SignName' => $this->smsSign,
                        'TemplateCode' => $this->templateCode,
                        'TemplateParam' => $templateParam,
                    ],
                ])
                ->request();
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }

}
