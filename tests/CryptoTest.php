<?php

use Yab\Laracogs\Encryption\LaracogsEncrypter;

class CryptoTest extends PHPUnit_Framework_TestCase
{
    protected $encrypter;

    public function setUp()
    {
        $this->encrypter = new LaracogsEncrypter('myapikey', 'mysessionkey');
    }

    public function testEncode()
    {
        $test = $this->encrypter->encrypt('js/card.sjs');
        $this->assertTrue(is_string($test));
        $this->assertEquals($test, 'K0xzM3hidFh6M0ZJdzFtWVJZaWR6QT09');
    }

    public function testDecode()
    {
        $test = $this->encrypter->decrypt('K0xzM3hidFh6M0ZJdzFtWVJZaWR6QT09');
        $this->assertTrue(is_string($test));
        $this->assertEquals($test, 'js/card.sjs');
    }
}
