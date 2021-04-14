<?php

namespace App\Console\Commands;

//用于存放公共方法
use Illuminate\Support\Facades\Cache;

trait Common
{
    /**
     * Redis
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function redis()
    {
        return app('redis.connection');
    }

    /**
     * 获取远程内容（接口数据获取）
     * @param $url
     * @param array $keysArr
     * @param string $mothod    默认get
     * @param array $headers
     * @param int $flag
     * @return bool|string
     */
    function fn_get_contents($url, $keysArr = array(), $mothod = 'get', $headers = [], $flag = 0)
    {
        $ch = curl_init();
        if (! $flag) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (strtolower($mothod) == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (is_array($keysArr))
            {
                $keysArr = http_build_query($keysArr, null, '&');
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
        } else {
            if (count($keysArr)) {
                $url = $url . "?" . http_build_query($keysArr);
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($headers) {
            foreach ($headers as $n => $v) {
                $headerArr[] = $n . ':' . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
        }

        $ret = curl_exec($ch);
        curl_close($ch);
        $ret = json_decode($ret,true);
        return $ret;
    }


}
