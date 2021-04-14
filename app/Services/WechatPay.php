<?php

namespace App\Services;

class WechatPay
{
    // 插件配置参数
    protected $appid;
    protected $mch_id;
    protected $secretKey;
    protected $notify_url;
    protected $redirect_url;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->appid = 'wx8ec706a12a886c18';
        $this->mch_id = '1606383737';
        $this->secretKey = 'SHYeGnIZYyHzLA6RHNK3NbAgIMOxdoCY';
        $this->notify_url = 'http://zhonghui.xinqianhui.cn/api/notify/wechat_notify';
        $this->redirect_url = '';
    }

    /**
     * APP支付（废弃）
     * @param array $params
     * @return bool|string
     */
    public function appPay2($params = [])
    {
        $url = 'https://api.mch.weixin.qq.com/v3/pay/transactions/app';
        $data = $this->makeParams($params);

        $result = $this->HttpRequest($url,$data);

        return $result;
    }

    /**
     * H5支付（废弃）
     * @param array $params
     * @return bool|string
     */
    public function h5Pay1($params = [])
    {
        $url = 'https://api.mch.weixin.qq.com/v3/pay/transactions/h5';
        $params['type'] = 'h5';
        $data = $this->makeParams($params);

        $header = [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: '.$params['user_agent'],
            'Authorization: WECHATPAY2-SHA256-RSA2048 mchid="'.$this->mch_id.'",nonce_str="593BEC0C930BF1AFEB40B4A08C8FB242",signature="uOVRnA4qG/MNnYzdQxJanN+zU+lTgIcnU9BxGw5dKjK+VdEUz2FeIoC+D5sB/LN+nGzX3hfZg6r5wT1pl2ZobmIc6p0ldN7J6yDgUzbX8Uk3sD4a4eZVPTBvqNDoUqcYMlZ9uuDdCvNv4TM3c1WzsXUrExwVkI1XO5jCNbgDJ25nkT/c1gIFvqoogl7MdSFGc4W4xZsqCItnqbypR3RuGIlR9h9vlRsy7zJR9PBI83X8alLDIfR1ukt1P7tMnmogZ0cuDY8cZsd8ZlCgLadmvej58SLsIkVxFJ8XyUgx9FmutKSYTmYtWBZ0+tNvfGmbXU7cob8H/4nLBiCwIUFluw==",timestamp="1554208460",serial_no="1DDE55AD98ED71D6EDD4A4A16996DE7B47773A8C"',
        ];

        $result = $this->HttpRequest($url,$data, $header);

        return $result;
    }

    /**
     * 组装请求参数
     * @param array $params
     * @return string
     */
    public function makeParams($params = [])
    {
        $description = $params['body'];
        $out_trade_no = $params['order_type'].$params['out_trade_no'];
        $total = $params['total_amount'];
        $data = [
            'appid' => $this->appid,
            'mchid' => $this->mch_id,
            'description' => $description,
            'out_trade_no' => $out_trade_no,
            'notify_url' => $this->notify_url,
            'amount' => json_encode(['total' => $total]),
            'sign_type'         => 'MD5',
        ];
        if(isset($params['type']) && $params['type'] == 'h5'){
            $data['scene_info'] = json_encode([
                'h5_info' => [
                    'type' => 'Wap'
                ]
            ]);
        }
        $data['sign'] = $this->GetSign($data);
        print_r(base64_encode($data['sign']));
        die();
        $data = json_encode($data);     //转为json字符串
        return $data;

    }

    /**
     * 支付入口
     * @param array $params
     * @return array
     */
    public function appPay($params = [])
    {
        // 获取支付参数
        $params['trade_type'] = 'APP';
        $data = $this->GetPayParams($params);

        // 请求接口处理
        $result = $this->XmlToArray($this->HttpRequest('https://api.mch.weixin.qq.com/pay/unifiedorder', $this->ArrayToXml($data)));
        if(!empty($result['return_code']) && $result['return_code'] == 'SUCCESS' && !empty($result['prepay_id']))
        {
            return $this->PayHandleReturn($data, $result, $params);
        }
        $msg = is_string($result) ? $result : (empty($result['return_msg']) ? '支付接口异常' : $result['return_msg']);
        if(!empty($result['err_code_des']))
        {
            $msg .= '-'.$result['err_code_des'];
        }
        return $msg;
    }

    /**
     * 支付入口
     * @param array $params
     * @return array
     */
    public function h5Pay($params = [])
    {
        // 获取支付参数
        $params['trade_type'] = 'MWEB';
        $data = $this->GetPayParams($params);

        // 请求接口处理
        $result = $this->XmlToArray($this->HttpRequest('https://api.mch.weixin.qq.com/pay/unifiedorder', $this->ArrayToXml($data)));
        if(!empty($result['return_code']) && $result['return_code'] == 'SUCCESS' && !empty($result['prepay_id']))
        {
            return $this->PayHandleReturn($data, $result, $params);
        }
        $msg = is_string($result) ? $result : (empty($result['return_msg']) ? '支付接口异常' : $result['return_msg']);
        if(!empty($result['err_code_des']))
        {
            $msg .= '-'.$result['err_code_des'];
        }
        return $msg;
    }

    /**
     * 支付返回处理
     * @param array $pay_params
     * @param array $data
     * @param array $params
     * @return array
     */
    private function PayHandleReturn($pay_params = [], $data = [], $params = [])
    {
        $redirect_url = empty($params['redirect_url']) ? $this->redirect_url : $params['redirect_url'];

        switch($pay_params['trade_type'])
        {
            // h5支付
            case 'MWEB' :
                if(!empty($params['order_id']))
                {
                    $data['mweb_url'] .= '&redirect_url='.$redirect_url;
                }
                $result = $data['mweb_url'];
                break;

            // APP支付
            case 'APP' :
                $pay_data = array(
                    'appid'         => $this->appid,
                    'partnerid'     => $this->mch_id,
                    'prepayid'      => $data['prepay_id'],
                    'package'       => 'Sign=WXPay',
                    'noncestr'      => md5(time().rand()),
                    'timestamp'     => (string) time(),
                );
                $pay_data['sign'] = $this->GetSign($pay_data);
                $result = $pay_data;
                break;
        }
        return $result;
    }

    /**
     * 获取支付参数
     * @param array $params
     * @return array
     */
    private function GetPayParams($params = [])
    {
        // 请求参数
        $data = [
            'appid'             => $this->appid,
            'mch_id'            => $this->mch_id,
            'body'              => $params['body'],
            'nonce_str'         => md5(time().$params['out_trade_no']),
            'notify_url'        => $this->notify_url,
            'openid'            => '',
            'out_trade_no'      => $params['order_type'].$params['out_trade_no'],
            'spbill_create_ip'  => '39.102.70.73',
            'total_fee'         => (int) (($params['total_amount']*1000)/10),
            'trade_type'        => $params['trade_type'],
            'attach'            => '',
            'sign_type'         => 'MD5',
        ];
        $data['sign'] = $this->GetSign($data);
        return $data;
    }

    /**
     * 支付回调处理
     * @param array $params
     * @return array
     */
    public function Respond($params = [])
    {
        $result = empty($GLOBALS['HTTP_RAW_POST_DATA']) ? $this->XmlToArray(file_get_contents('php://input')) : $this->XmlToArray($GLOBALS['HTTP_RAW_POST_DATA']);

        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS' && $result['sign'] == $this->GetSign($result))
        {
            return DataReturn('支付成功', 0, $this->ReturnData($result));
        }
        return DataReturn('处理异常错误', -100);
    }

    /**
     * [ReturnData 返回数据统一格式]
     * @param $data
     * @return mixed
     */
    private function ReturnData($data)
    {
        // 返回数据固定基础参数
        $data['trade_no']       = $data['transaction_id'];  // 支付平台 - 订单号
        $data['buyer_user']     = $data['openid'];          // 支付平台 - 用户
        $data['out_trade_no']   = $data['out_trade_no'];    // 本系统发起支付的 - 订单号
        $data['subject']        = $data['attach'];          // 本系统发起支付的 - 商品名称
        $data['pay_price']      = $data['total_fee']/100;   // 本系统发起支付的 - 总价
        return $data;
    }

    /**
     * 退款处理
     * @param array $params
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function Refund($params = [])
    {
        // 参数
        $p = [
            [
                'checked_type'      => 'empty',
                'key_name'          => 'order_no',
                'error_msg'         => '订单号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'trade_no',
                'error_msg'         => '交易平台订单号不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'pay_price',
                'error_msg'         => '支付金额不能为空',
            ],
            [
                'checked_type'      => 'empty',
                'key_name'          => 'refund_price',
                'error_msg'         => '退款金额不能为空',
            ],
        ];
        $ret = ParamsChecked($params, $p);
        if($ret !== true)
        {
            return DataReturn($ret, -1);
        }

        // 证书是否配置
        if(empty($this->config['apiclient_cert']) || empty($this->config['apiclient_key']))
        {
            return DataReturn('证书未配置', -1);
        }

        // 退款原因
        $refund_reason = empty($params['refund_reason']) ? $params['order_no'].'订单退款'.$params['refund_price'].'元' : $params['refund_reason'];

        // appid，默认使用公众号appid
        $appid = (!isset($params['client_type']) || in_array($params['client_type'], ['pc', 'h5'])) ? $this->config['appid'] : $this->config['mini_appid'];

        // 请求参数
        $data = [
            'appid'             => $appid,
            'mch_id'            => $this->config['mch_id'],
            'nonce_str'         => md5(time().rand().$params['order_no']),
            'sign_type'         => 'MD5',
            'transaction_id'    => $params['trade_no'],
            'out_refund_no'     => $params['order_no'].GetNumberCode(6),
            'total_fee'         => (int) (($params['pay_price']*1000)/10),
            'refund_fee'        => (int) (($params['refund_price']*1000)/10),
            'refund_desc'       => $refund_reason,
        ];
        $data['sign'] = $this->GetSign($data);

        // 请求接口处理
        $result = $this->XmlToArray($this->HttpRequest('https://api.mch.weixin.qq.com/secapi/pay/refund', $this->ArrayToXml($data), true));
        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS' && isset($result['return_code']) && $result['return_code'] == 'SUCCESS')
        {
            // 统一返回格式
            $data = [
                'out_trade_no'  => isset($result['out_trade_no']) ? $result['out_trade_no'] : '',
                'trade_no'      => isset($result['transaction_id']) ? $result['transaction_id'] : (isset($result['err_code_des']) ? $result['err_code_des'] : ''),
                'buyer_user'    => isset($result['refund_id']) ? $result['refund_id'] : '',
                'refund_price'  => isset($result['refund_fee']) ? $result['refund_fee']/100 : 0.00,
                'return_params' => $result,
            ];
            return DataReturn('退款成功', 0, $data);
        }
        $msg = is_string($result) ? $result : (empty($result['err_code_des']) ? '退款接口异常' : $result['err_code_des']);
        if(!empty($result['return_msg']))
        {
            $msg .= '-'.$result['return_msg'];
        }
        return DataReturn($msg, -1);
    }

    /**
     * 签名生成
     * @param array $params
     * @return string
     */
    private function GetSign($params = [])
    {
        ksort($params);
        $sign  = '';
        foreach($params as $k=>$v)
        {
            if($k != 'sign' && $v != '' && $v != null)
            {
                $sign .= "$k=$v&";
            }
        }
        return strtoupper(md5($sign.'key='.$this->secretKey));
    }

    /**
     * 数组转xml
     * @param $data
     * @return string
     */
    private function ArrayToXml($data)
    {
        $xml = '<xml>';
        foreach($data as $k=>$v)
        {
            $xml .= '<'.$k.'>'.$v.'</'.$k.'>';
        }
        $xml .= '</xml>';
        return $xml;
    }

    /**
     * xml转数组
     * @param $xml
     * @return mixed|string
     */
    private function XmlToArray($xml)
    {
        if(!$this->XmlParser($xml))
        {
            return is_string($xml) ? $xml : '接口返回数据有误';
        }

        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }


    /**
     * 判断字符串是否为xml格式
     * @param $string
     * @return bool|mixed
     */
    function XmlParser($string)
    {
        $xml_parser = xml_parser_create();
        if(!xml_parse($xml_parser, $string, true))
        {
            xml_parser_free($xml_parser);
            return false;
        } else {
            return (json_decode(json_encode(simplexml_load_string($string)),true));
        }
    }

    /**
     * [HttpRequest 网络请求]
     *
     * @blog     http://gong.gg/
     * @version  1.0.0
     * @datetime 2017-09-25T09:10:46+0800
     * @param    string          $url
     * @param    array           $data
     * @param    [boolean]         $use_cert
     * @param    int             $second
     * @return   mixed
     */
    /**
     * @param $url              [请求url]
     * @param $data             [发送数据]
     * @param bool $use_cert    [是否需要使用证书]
     * @param int $second       [超时]
     * @return bool|string      [请求返回数据]
     */
    private function HttpRequest($url, $data, $use_cert = false, $second = 30)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_POST           => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_TIMEOUT        => $second,
        );

        if($use_cert == true)
        {
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            $apiclient = $this->GetApiclientFile();
            $options[CURLOPT_SSLCERTTYPE] = 'PEM';
            $options[CURLOPT_SSLCERT] = $apiclient['cert'];
            $options[CURLOPT_SSLKEYTYPE] = 'PEM';
            $options[CURLOPT_SSLKEY] = $apiclient['key'];
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        //返回结果
        if($result)
        {
            curl_close($ch);
            return $result;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return "curl出错，错误码:$error";
        }
    }
}
