<?php


namespace Omnipay\VrPayment\Message;


class RefundRequest extends AbstractRequest
{
    protected function getEndpointRoute()
    {
        return '/v1/payments/' . $this->getTransactionReference() .'';
    }

    /**
     * Set up the base data for a purchase request
     *
     * @return mixed[]
     */
    public function getData()
    {
        $this->validate('amount', 'currency', 'transactionReference');

        $data = parent::getData();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['paymentType'] = 'RF';
        return $data;
    }

    /**
     *
     */
    protected function createResponse($data)
    {
        return $this->response = new RefundResponse($this, $data);
    }
}