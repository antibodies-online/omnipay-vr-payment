<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VrPayment\Message\PurchaseRequest;

class AuthorizeRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'token' => 'foo'
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('5.00', $data['amount']);
        $this->assertSame('USD', $data['currency']);
        $this->assertSame('PA', $data['paymentType']);
        $this->assertTrue(array_key_exists('testMode', $data));
    }

    public function testDataWithToken()
    {
        $this->request->setToken('xyz');
        $this->assertSame('xyz', $this->request->getToken());
    }

}