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

}