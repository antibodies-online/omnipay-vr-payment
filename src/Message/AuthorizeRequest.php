<?php


namespace Omnipay\VrPayment\Message;


class AuthorizeRequest extends PurchaseRequest
{
    public function getData()
    {
        $data = parent::getData();
        $data['paymentType'] = 'PA';
        return $data;
    }

    /**
     *
     */
    protected function createResponse($data)
    {
        return $this->response = new AuthorizeResponse($this, $data);
    }
}