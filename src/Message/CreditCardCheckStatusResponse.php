<?php


namespace Omnipay\VrPayment\Message;

class CreditCardCheckStatusResponse extends AbstractResponse
{
    private $token;

    private $cardType;

    public function __construct(CreditCardCheckStatusRequest $param, $data)
    {
        parent::__construct($param, $data);
        $this->token = $data['id'];
        $this->cardType = $data['paymentBrand'];
    }

    public function getTransactionReference()
    {
        return $this->token;
    }

    public function getCardType()
    {
        return $this->cardType;
    }

    protected function isExpectedResultCode($resultCode)
    {
        return (bool)preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $resultCode);
    }
}