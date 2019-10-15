<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'transactionReference' => 'foo'
            )
        );
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage The transactionReference parameter is required
     */
    public function testTransactionReferenceMandatory() {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '5.00',
                'currency' => 'USD',
                'paymentType' => 'CP'
            )
        );
        $this->request->getData();
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('5.00', $data['amount']);
        $this->assertSame('USD', $data['currency']);
        $this->assertSame('CP', $data['paymentType']);
    }
}