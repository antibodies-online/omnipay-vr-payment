<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends OmnipayAbstractResponse
{

    public function isSuccessful()
    {
        return (bool)$this->isExpectedResultCode($this->getData()['result']['code']);
    }

    abstract protected function isExpectedResultCode($resultCode);
}