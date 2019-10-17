<?php

namespace Omnipay\VrPayment\Message;

use Mockery;
use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\VrPayment\Message\AbstractRequest')->makePartial();
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

    public function testGetSimulation()
    {
        $this->assertSame($this->request, $this->request->setSimulation(true));
        $this->assertSame(true, $this->request->getSimulation());
    }

    public function testGetData()
    {
        $this->request->initialize(['card' => $this->getValidCard()]);

        $data = $this->request->getData();

        $this->assertSame('Billstown', $data['billing.city']);
        $this->assertSame('US', $data['billing.country']);
        $this->assertSame('123 Billing St', $data['billing.street1']);
        $this->assertSame('Billsville', $data['billing.street2']);
        $this->assertSame('12345', $data['billing.postcode']);
        $this->assertSame('CA', $data['billing.state']);
        $this->assertSame('Example', $data['customer.givenName']);
        $this->assertSame('User', $data['customer.surname']);
    }

}