<?php
namespace Lockr\KeyWrapper;

class LockrAes128CtrSha256RawKeyWrapper implements KeyWrapperInterface
{
    const PREFIX = '$1$';

    const METHOD = 'aes-128-ctr';

    const HMAC_KEY_LEN = 32;
    const KEY_LEN = 16;
    const IV_LEN = 16;

    /**
     * {@inheritdoc}
     */
    public static function enabled()
    {
        return function_exists('openssl_encrypt');
    }

    /**
     * {@inheritdoc}
     */
    public static function encrypt($plaintext)
    {
        $key = random_bytes(self::KEY_LEN);
        $iv = random_bytes(self::IV_LEN);
        $hmac_key = random_bytes(self::HMAC_KEY_LEN);
        return self::doEncrypt($plaintext, $key, $iv, $hmac_key);
    }

    /**
     * {@inheritdoc}
     */
    public static function reencrypt($plaintext, $wrapping_key)
    {
        $wrapping_key = substr($wrapping_key, strlen(self::PREFIX));
        $wrapping_key = base64_decode($wrapping_key);
        $key = substr($wrapping_key, 0, self::KEY_LEN);
        $hmac_key = substr($wrapping_key, self::KEY_LEN);
        $iv = random_bytes(self::IV_LEN);
        return self::doEncrypt($plaintext, $key, $iv, $hmac_key);
    }

    /**
     * {@inheritdoc}
     */
    public static function decrypt($ciphertext, $wrapping_key)
    {
        $wrapping_key = substr($wrapping_key, strlen(self::PREFIX));
        $wrapping_key = base64_decode($wrapping_key);
        $key = substr($wrapping_key, 0, self::KEY_LEN);
        $hmac_key = substr($wrapping_key, self::KEY_LEN);

        $cipherdata = substr($ciphertext, 0, -self::HMAC_KEY_LEN);
        $hmac = substr($ciphertext, -self::HMAC_KEY_LEN);
        if (!self::hashEquals($hmac, self::hmac($cipherdata, $hmac_key))) {
            return false;
        }

        $iv = substr($cipherdata, 0, self::IV_LEN);
        $ciphertext = substr($cipherdata, self::IV_LEN);
        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        if ($plaintext === false) {
            return false;
        }
        return $plaintext;
    }

    private static function doEncrypt($plaintext, $key, $iv, $hmac_key)
    {
        $ciphertext = openssl_encrypt(
            $plaintext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $cipherdata = $iv . $ciphertext;
        $hmac = self::hmac($cipherdata, $hmac_key);
        return [
            'ciphertext' => $cipherdata . $hmac,
            'encoded' => self::PREFIX . base64_encode($key . $hmac_key),
        ];
    }

    private static function hmac($data, $key)
    {
        return hash_hmac('sha256', self::METHOD . $data, $key, true);
    }

    private static function hashEquals($left, $right)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($left, $right);
        }

        $ret = 0;

        if (strlen($left) !== strlen($right)) {
            $right = $left;
            $ret = 1;
        }

        $res = $left ^ $right;

        for ($i = strlen($res) - 1; $i >= 0; --$i) {
            $ret |= ord($res[$i]);
        }

        return !$ret;
    }
}

// ex: ts=4 sts=4 sw=4 et:
