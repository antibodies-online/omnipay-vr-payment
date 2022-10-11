<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    public function setUp(): void
    {
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'transactionReference' => 'foo'
            )
        );
    }

    public function testTransactionReferenceMandatory() {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The transactionReference parameter is required');
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'paymentType' => 'RF'
            )
        );
        $this->request->getData();
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('5.00', $data['amount']);
        $this->assertSame('USD', $data['currency']);
        $this->assertSame('RF', $data['paymentType']);
    }

}