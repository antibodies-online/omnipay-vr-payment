<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;

class CreditCardCheckResponseTest extends TestCase
{

    private $response;

    public function setUp()
    {
        $request = new CreditCardCheckRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(
            array('endpoint' => 'https://example.com')
        );

        $this->setMockHttpResponse('CreditCardCheckResponseSuccess.txt');
        $this->response = $request->send();
    }

    public function testGetCheckoutId() {
        $this->assertSame('2CFB6EE6F889C290A469A5C75755267D.uat01-vm-tx03', $this->response->getCheckoutId());
    }

    public function testGetPaymentFormJsUrl() {
        $this->assertSame('https://example.com/v1/paymentWidgets.js?checkoutId=2CFB6EE6F889C290A469A5C75755267D.uat01-vm-tx03', $this->response->getPaymentFormJsUrl());
    }

    public function testSendSuccess()
    {
        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame('Omnipay\VrPayment\Message\CreditCardCheckResponse', get_class($this->response));
    }
}
