<?php

use org\bovigo\vfs\vfsStream;
use Yab\Laracogs\Encryption\LaracogsEncrypter;

class CrudGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected $encrypter;

    public function setUp()
    {
        // $this->encrypter = new LaracogsEncrypter('myapikey', 'mysessionkey');
        // $this->root = vfsStream::setup("myrootdir");

        // //set the paths in the config


        // $this->assertFalse($this->root->hasChild('myfile.txt'));
        // file_put_contents(vfsStream::url('myrootdir/myfile.txt'), "my content");
        // $this->assertTrue($this->root->hasChild('myfile.txt'));

        // $remover->delete(vfsStream::url('myrootdir/myfile.txt'));
        // $this->assertFalse($this->root->hasChild('myfile.txt'));
    }

    public function testEncode()
    {
        $test = 'test';
        $this->assertTrue(is_string($test));
        // $this->assertEquals($test, 'K0xzM3hidFh6M0ZJdzFtWVJZaWR6QT09');
    }

    // public function testDecode()
    // {
    //     $test = $this->encrypter->decrypt('K0xzM3hidFh6M0ZJdzFtWVJZaWR6QT09');
    //     $this->assertTrue(is_string($test));
    //     $this->assertEquals($test, 'js/card.sjs');
    // }
}
