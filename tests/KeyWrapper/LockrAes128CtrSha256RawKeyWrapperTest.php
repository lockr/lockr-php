<?php
namespace Lockr\Tests\KeyWrapper;

use PHPUnit\Framework\TestCase;

use Lockr\KeyWrapper\LockrAes128CtrSha256RawKeyWrapper as KeyWrapper;

class LockrAes128CtrSha256RawKeyWrapperTest extends TestCase
{
    public function testEncryptsData()
    {
        $text = 'abcd';
        $data = KeyWrapper::encrypt($text);
        $plaintext = KeyWrapper::decrypt($data['ciphertext'], $data['encoded']);
        $this->assertSame($text, $plaintext);
    }

    public function testReencryptsData()
    {
        $data = KeyWrapper::encrypt('aaaa');
        $wk = $data['encoded'];
        $text = 'abcd';
        $data = KeyWrapper::reencrypt($text, $wk);
        $plaintext = KeyWrapper::decrypt($data['ciphertext'], $wk);
        $this->assertSame($text, $plaintext);
    }
}

// ex: ts=4 sts=4 sw=4 et:
