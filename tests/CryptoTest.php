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
        $enc = $this->encrypter->encrypt('js/card.sjs');
        $dec = $this->encrypter->decrypt($enc);
        $this->assertTrue(is_string($dec));
        $this->assertEquals($dec, 'js/card.sjs');
    }

    public function testDecode()
    {
        $test = $this->encrypter->decrypt('YzllZTlhMTJkMGJhZGI2ZFF0TkkybnRFZWhnRFhGY2ZzMFFKRGc9PQ--');
        $this->assertTrue(is_string($test));
        $this->assertEquals($test, 'js/card.sjs');
    }

    public function testUuid()
    {
        $test = $this->encrypter->uuid();
        $this->assertTrue(is_string($test));
        $this->assertEquals(strlen($test), 36);
        $this->assertContains('-', $test);
    }
}


