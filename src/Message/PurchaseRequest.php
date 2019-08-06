<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Common\Message\ResponseInterface;

class PurchaseRequest extends AbstractRequest
{

    protected function getEndpointRoute()
    {
        return '/v1/registrations/' . $this->getToken() .'/payments';
    }

    /**
     * Set up the base data for a purchase request
     *
     * @return mixed[]
     */
    public function getData()
    {
        $this->validate('amount', 'currency', 'token');

        $data = parent::getData();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['merchantTransactionId'] = $this->getTransactionId();
        $data['paymentType'] = 'DB';
        return $data;
    }

    /**
     *
     */
    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}