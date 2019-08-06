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

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CaptureResponseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame($response->getData()['paymentType'], 'CP');
        $this->assertSame('8ac7a49f6c619208016c61c907ff73bc', $response->getTransactionReference());
        $this->assertSame('Omnipay\VrPayment\Message\CaptureResponse', get_class($response));
    }

}