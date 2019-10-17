<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{

    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'entityId' => 'foo'
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $this->assertSame('foo', $data['entityId']);
        $this->assertArrayNotHasKey('testMode', $data);
    }

}
