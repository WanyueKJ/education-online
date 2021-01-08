<?php

namespace Qiniu;

class MacTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mac
     */
    protected $mac;

    public function setUp()
    {
        $this->mac = new Mac('test', 'test');
    }

    public function testSign()
    {
        $this->assertEquals('test:DJRRXBXlCVuKh6ULoN87847QX-Y=', $this->mac->sign('test'));
    }

    public function testSignWithData()
    {
        $this->assertEquals('test:b25MJSh2VIQPTdMvs7POrsTIvEE=:dGVzdA==', $this->mac->signWithData('test'));
    }

    public function testSignRequest()
    {
        $this->assertEquals('test:P2jjL3PIgphwFTw9AV_zeWKl_1Q=', $this->mac->signRequest('/stat'));
    }
}