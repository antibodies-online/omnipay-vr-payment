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

}