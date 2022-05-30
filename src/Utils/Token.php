<?php

namespace Fahriztx\EzLaravelSsoClient\Utils;

class Token
{
    static function checkToken($token)
    {
        $token = static::parseToken($token);

        if (!$token) {
            return ['status' => false, 'message' => 'Invalid Token!'];
        }

        if (!static::checkSignature($token)) {
            return ['status' => false, 'message' => 'Invalid Signature!'];
        }

        if (strtotime(date('Y-m-d H:i:s')) >= $token['payload']->exp) {
            return ['status' => false, 'message' => 'Token Expired!'];
        }

        return ['status' => true, 'data' => $token['payload']];
    }

    static function parseToken($token)
    {
        $token = explode('.', $token);

        if (count($token) != 3) {
            return false;
        }

        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        return [
            'header'    => json_decode(base64_decode($header)),
            'payload'   => json_decode(base64_decode($payload)),
            'signature' => $signature
        ];
    }

    static function generateSignature($string, $secret)
    {
        return str_replace(['+', '=', '/'], ['-', '', '_'], base64_encode(hex2bin(hash_hmac('sha256', $string, $secret))));
    }

    static function checkSignature($token)
    {
        $hp = str_replace(['+', '=', '/'], ['-', '', '_'], base64_encode(json_encode($token['header'])).'.'.base64_encode(json_encode($token['payload'])));
        
        $gsign = static::generateSignature($hp, config('ezlaravelssoclient.client_secret'));

        if ($token['signature'] != $gsign) {
            return false;
        }

        return true;
    }
}