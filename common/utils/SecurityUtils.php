<?php
namespace common\utils;

class SecurityUtils
{
    const key = 'jeeemooo';

    public static function encrypt($data)
    {
        $prep_code = serialize($data);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, self::key, $prep_code, MCRYPT_MODE_ECB);
        $encrypted = base64_encode($encrypt);
        $encrypted = str_replace("+", "-", $encrypted);
        $encrypted = str_replace("/", "_", $encrypted);
        return $encrypted;
    }

    public static function decrypt($str)
    {
        $str = str_replace("-", "+", $str);
        $str = str_replace("_", "/", $str);
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, self::key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
            return unserialize($str);
        }
        return unserialize($str);
    }

}