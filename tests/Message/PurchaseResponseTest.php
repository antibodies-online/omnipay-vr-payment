<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{

    private $response;

    public function setUp()
    {
        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'token' => 'foo'
            )
        );
        $this->setMockHttpResponse('PurchaseResponseSuccess.txt');
        $this->response = $request->send();
    }

    public function testIsRedirect()
    {
        $this->assertFalse($this->response->isRedirect());
    }

    public function testGetRedirectUrl() {
        $this->assertSame('', $this->response->getRedirectUrl());
    }

    public function testGetRedirectData() {
        $this->assertSame([], $this->response->getRedirectData());
    }

    public function testGetRedirectMethod() {
        $this->assertSame('', $this->response->getRedirectMethod());
    }

    public function testGetTransactionReference() {
        $this->assertSame('8ac7a49f6c619208016c61ca7b0c7680', $this->response->getTransactionReference());
    }

    public function testSendSuccess()
    {
        $this->assertTrue($this->response->isSuccessful());
        $this->assertSame($this->response->getData()['paymentType'], 'DC');
        $this->assertSame('Omnipay\VrPayment\Message\PurchaseResponse', get_class($this->response));
    }
}
