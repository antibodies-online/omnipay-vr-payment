<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VrPayment\Message\PurchaseRequest;

class PurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
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
    }

    public function testDataWithToken()
    {
        $this->request->setToken('xyz');
        $this->assertSame('xyz', $this->request->getToken());
    }


    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseResponseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame($response->getData()['paymentType'], 'DC');
        $this->assertSame('8ac7a49f6c619208016c61ca7b0c7680', $response->getTransactionReference());
        $this->assertSame('Omnipay\VrPayment\Message\PurchaseResponse', get_class($response));
    }

}