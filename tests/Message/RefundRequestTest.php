<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    public function setUp()
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

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('RefundResponseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame($response->getData()['paymentType'], 'RF');
        $this->assertSame('8ac7a4a06c60faba016c6111f9574159', $response->getTransactionReference());
        $this->assertSame('Omnipay\VrPayment\Message\RefundResponse', get_class($response));
    }

}