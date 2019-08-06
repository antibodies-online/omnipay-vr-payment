<?php


namespace Omnipay\VrPayment\Message;


use Mockery;
use Omnipay\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockBuilder;
use Symfony\Component\HttpFoundation\Request;

class CreditCardCheckStatusRequestTest extends TestCase
{
    public function setUp()
    {
        $mock = Mockery::mock(Request::class);
        $mock->shouldReceive('getQueryString')
            ->times(1)
            ->andReturn('resourcePath=foobar');

        $this->request = new CreditCardCheckStatusRequest($this->getHttpClient(), $mock);
        $this->request->initialize(
            array()
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame([], $data);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreditCardCheckStatusResponseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('8ac7a4a06c61a259016c62136baa6797', $response->getTransactionReference());
        $this->assertSame('VISA', $response->getCardType());
        $this->assertSame('Omnipay\VrPayment\Message\CreditCardCheckStatusResponse', get_class($response));
    }

}