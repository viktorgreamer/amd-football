<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.03.2018
 * Time: 13:40
 */

namespace app\models;


class Curling
{
    public static function getting($url, $proxy = '', $ref = '')
    {
        $host = self::getHost($url);


        $header = [];
        if ($host) {
            $header[] = "Host: ".$host;

        }
        if (!$ref) $ref = $host;
        $header[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0";
        $header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
        $header[] = "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3";
        $header[] = "Accept-Encoding: gzip, deflate, br";
        $header[] = "Accept: */*";
        $header[] = "Connection: keep-alive";
        $header[] = "DNT: 1";
        $header[] = "Referer: $ref";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 90);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_PROXY, $proxy);
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie2.txt');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      //  curl_setopt($curl, CURLOPT_HEADER, true);
        // $response = iconv("windows-1251","UTF-8",  gzdecode(curl_exec($curl)));
         $response = curl_exec($curl);
        curl_close($curl);
        //  echo $response;
        return $response;

    }

    public static function getHost($url) {

        if (preg_match("/https:\/\/(.+)\//U", $url, $output_array)) {
            return $output_array[1];
        }


    }
}