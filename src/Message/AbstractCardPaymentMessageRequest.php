<?php

namespace Omnipay\VrPayment\Message;

abstract class AbstractCardPaymentMessageRequest extends AbstractRequest
{

    public function getData()
    {
        $data = parent::getData();
        if (false !== $this->getSimulation()) {
            $data['testMode'] = $this->getSimulation();
        }
        return $data;
    }
}
