<?php

namespace Omnipay\VrPayment;

use Omnipay\Tests\GatewayTestCase;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class VrPaymentGatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $this->getHttpRequest());
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
}
