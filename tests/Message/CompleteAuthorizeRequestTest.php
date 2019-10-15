<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;

class CompleteAuthorizeRequestTest extends TestCase
{

    public function setUp()
    {
        $this->request = new CompleteAuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompleteAuthorizeResponseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame($response->getData()['paymentType'], 'PA');
        $this->assertSame($response->getData()['resultDetails']['AcquirerResponse'], 'Success');
        $this->assertSame('8ac7a49f6c619208016c61ca7b0c7680', $response->getTransactionReference());
        $this->assertSame('Omnipay\VrPayment\Message\CompleteAuthorizeResponse', get_class($response));
    }

}
