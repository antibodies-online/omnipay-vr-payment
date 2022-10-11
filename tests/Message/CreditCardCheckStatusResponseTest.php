<?php

namespace Omnipay\VrPayment\Message;

use Mockery;
use Omnipay\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockBuilder;
use Symfony\Component\HttpFoundation\Request;

class CreditCardCheckStatusResponseTest extends TestCase
{

    private $response;

    public function setUp(): void
    {
        $mockHttpRequest = Mockery::mock(Request::class);
        $mockHttpRequest->shouldReceive('getQueryString')
            ->times(1)
            ->andReturn('resourcePath=foobar');

        $request = new CreditCardCheckStatusRequest($this->getHttpClient(), $mockHttpRequest);
        $request->initialize(
            array()
        );

        $this->setMockHttpResponse('CreditCardCheckStatusResponseSuccess.txt');
        $this->response = $request->send();
    }

    public function testGetTransactionReference()
    {
        $this->assertSame('8ac7a4a06c61a259016c62136baa6797', $this->response->getTransactionReference());
    }

    public function testGetCardType()
    {
        $this->assertSame('VISA', $this->response->getCardType());
    }

    public function testSendSuccess()
    {
        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isRedirect());
        $this->assertSame('Omnipay\VrPayment\Message\CreditCardCheckStatusResponse', get_class($this->response));
    }

}
