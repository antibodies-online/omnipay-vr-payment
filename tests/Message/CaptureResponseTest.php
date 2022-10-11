<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;

class CaptureResponseTest extends TestCase
{

    private $response;

    public function setUp(): void
    {
        $request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'transactionReference' => 'foo'
            )
        );

        $this->setMockHttpResponse('CaptureResponseSuccess.txt');
        $this->response = $request->send();
    }

    public function testSendSuccess()
    {
        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame($this->response->getData()['paymentType'], 'CP');
        $this->assertSame('8ac7a49f6c619208016c61c907ff73bc', $this->response->getTransactionReference());
        $this->assertSame('Omnipay\VrPayment\Message\CaptureResponse', get_class($this->response));
    }

    public function testGetData()
    {
        $data = $this->response->getData();
        $this->assertTrue(array_key_exists('result', $data));
        $this->assertSame('000.100.110', $data['result']['code']);
        $this->assertSame('Request successfully processed in \'Merchant in Integrator Test Mode\'', $data['result']['description']);
    }

    public function testGetTransactionReference()
    {
        $this->assertSame('8ac7a49f6c619208016c61c907ff73bc', $this->response->getTransactionReference());
    }
}