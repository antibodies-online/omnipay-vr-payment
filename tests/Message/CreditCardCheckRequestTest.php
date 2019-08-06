<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Tests\TestCase;

class CreditCardCheckRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CreditCardCheckRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array()
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('true', $data['createRegistration']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreditCardCheckResponseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('2CFB6EE6F889C290A469A5C75755267D.uat01-vm-tx03', $response->getCheckoutId());
        $this->assertSame('https://test.vr-pay-ecommerce.de/v1/paymentWidgets.js?checkoutId=2CFB6EE6F889C290A469A5C75755267D.uat01-vm-tx03', $response->getPaymentFormJsUrl());
        $this->assertSame('Omnipay\VrPay\Message\CreditCardCheckResponse', get_class($response));
    }

}