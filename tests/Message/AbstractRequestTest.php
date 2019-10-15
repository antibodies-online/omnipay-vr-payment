<?php

namespace Omnipay\VrPayment\Message;

use Mockery;
use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\VrPayment\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testEntityId()
    {
        $this->assertSame($this->request, $this->request->setEntityId('abc123'));
        $this->assertSame('abc123', $this->request->getEntityId());
    }

    public function testAccessToken()
    {
        $this->assertSame($this->request, $this->request->setAccessToken('abc123'));
        $this->assertSame('abc123', $this->request->getAccessToken());
    }

    public function testEndpoint()
    {
        $this->assertSame($this->request, $this->request->setEndpoint('abc123'));
        $this->assertSame('abc123', $this->request->getEndpoint());
    }

    public function testGetData()
    {
        $parameter = [
            'number' => '4111111111111111',
            'expiryMonth' => '10',
            'expiryYear' => '22',
            'cvv' => '123',
            'billingFirstName' => 'John',
            'billingLastName' => 'Doe',
            'billingCompany' => 'acme Inc.',
            'billingAddress1' => '721 Fifth Avenue',
            'billingAddress2' => '58th Floor',
            'billingCity' => 'New York City',
            'billingPostcode' => '10022',
            'billingState' => 'NY',
            'billingCountry' => 'United States',
            'email' => 'test@example.com',
        ];

        $card = new CreditCard($parameter);

        $this->request->initialize(['card' => $card]);

        $data = $this->request->getData();

        $this->assertSame('New York City', $data['billing.city']);
        $this->assertSame('United States', $data['billing.country']);
        $this->assertSame('721 Fifth Avenue', $data['billing.street1']);
        $this->assertSame('58th Floor', $data['billing.street2']);
        $this->assertSame('10022', $data['billing.postcode']);
        $this->assertSame('NY', $data['billing.state']);
        $this->assertSame('acme Inc.', $data['customer.companyName']);
        $this->assertSame('test@example.com', $data['customer.email']);
        $this->assertSame('John', $data['customer.givenName']);
        $this->assertSame('Doe', $data['customer.surname']);
    }
}