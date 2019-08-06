<?php


namespace Omnipay\VrPayment\Message;


class CreditCardCheckRequest extends AbstractRequest
{
    public function getEndpointRoute()
    {
        return '/v1/checkouts';
    }

    public function getData()
    {
        $data = parent::getData();
        $data['createRegistration'] = 'true';
        return $data;
    }

    /**
     *
     */
    protected function createResponse($data)
    {
        return $this->response = new CreditCardCheckResponse($this, $data);
    }
}