<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    private $response;

    public function setUp()
    {
        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'transactionReference' => 'foo'
            )
        );
        $this->setMockHttpResponse('RefundResponseSuccess.txt');
        $this->response = $request->send();
    }

    public function testGetTransactionReference()
    {
        $this->assertSame('8ac7a4a06c60faba016c6111f9574159', $this->response->getTransactionReference());
    }

    public function testSendSuccess()
    {
        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame($this->response->getData()['paymentType'], 'RF');
        $this->assertSame('Omnipay\VrPayment\Message\RefundResponse', get_class($this->response));
    }
}
