<?php


namespace Omnipay\VrPayment\Message;


use Mockery;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\VrPay\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testEntityId()
    {
        $this->assertSame($this->request, $this->request->setEntityId('abc123'));
        $this->assertSame('abc123', $this->request->getEntityId());
    }

    public function testAccessToken()
    {
        $this->assertSame($this->request, $this->request->setAccessToken('abc123'));
        $this->assertSame('abc123', $this->request->getAccessToken());
    }

    public function testEndpoint()
    {
        $this->assertSame($this->request, $this->request->setEndpoint('abc123'));
        $this->assertSame('abc123', $this->request->getEndpoint());
    }
}