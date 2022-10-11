<?php

namespace Omnipay\VrPayment;

use Omnipay\Tests\GatewayTestCase;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class VrPaymentGatewayTest extends GatewayTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $httpRequest = $this->getHttpRequest();
        $httpRequest->initialize([],[],[],[],[],['QUERY_STRING' => 'resourcePath=foobar']);

        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $httpRequest);
        $this->gateway->setEntityId('foo');

        $this->options = array(
            'amount' => '5.00',
            'token' => 'bar'
        );
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase($this->options);

        $this->assertInstanceOf('Omnipay\VrPayment\Message\PurchaseRequest', $request);
        $this->assertEquals('bar', $request->getToken());
        $this->assertEquals('500', $request->getAmountInteger());
    }

    public function testEndpointDependsOnTestMode() 
    {
        $this->assertSame(VrPaymentGateway::ENDPOINT_LIVE, $this->gateway->getEndpoint());
        $this->gateway->setTestMode(true);
        $this->assertSame(VrPaymentGateway::ENDPOINT_TEST, $this->gateway->getEndpoint());
        $this->gateway->setTestMode(false);
        $this->assertSame(VrPaymentGateway::ENDPOINT_LIVE, $this->gateway->getEndpoint());
    }

    public function testGetEntityId()
    {
        $this->assertSame('foo', $this->gateway->getEntityId());
    }

    public function testGetAccessToken()
    {
        $this->assertSame('Omnipay\VrPayment\VrPaymentGateway', get_class($this->gateway->setAccessToken('access-token')));
        $this->assertSame('access-token', $this->gateway->getAccessToken());
    }

    public function testGetNotificationDecryptionKey()
    {
        $this->assertSame('Omnipay\VrPayment\VrPaymentGateway', get_class($this->gateway->setNotificationDecryptionKey('decrypt')));
        $this->assertSame('decrypt', $this->gateway->getNotificationDecryptionKey());
    }

    public function testCheckCreditCardCheck()
    {
        $response = $this->gateway->creditCardCheck(['card' => $this->getValidCard()]);
        $this->assertSame('Omnipay\VrPayment\Message\CreditCardCheckRequest', get_class($response));
    }

    public function testCheckCreditCardCheckStatus()
    {
        $response = $this->gateway->creditCardCheckStatus(['card' => $this->getValidCard()]);
        $this->assertSame('Omnipay\VrPayment\Message\CreditCardCheckStatusRequest', get_class($response));
    }

}
