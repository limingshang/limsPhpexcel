<?php

namespace Curl;

/**
 * Class Curl
 * @author lims
 */
class Curl
{
    //User-Agent 头
    public $userAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';

    /**
     * get
     *
     * @return array
     */
    public function get($url, $userAgent = null, $header = 0)
    {
        $this->userAgent = $userAgent ? $userAgent : $this->userAgent;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        $ret['data'] = curl_exec($curl);
        $ret['info'] = curl_getinfo($curl);
        curl_close($curl);
        return $ret;
    }

    /**
     * post
     *
     * @return array
     */
    public function post($url, $param = null, $userAgent = null, $header = 0)
    {
        $this->userAgent = $userAgent ? $userAgent : $this->userAgent;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        if ($param != null) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        }
        $ret['data'] = curl_exec($curl);
        $ret['info'] = curl_getinfo($curl);
        curl_close($curl);
        return $ret;
    }
}
