<?php


namespace Omnipay\VrPayment\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    protected function isExpectedResultCode($resultCode)
    {
        return (bool)preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $resultCode);
    }

    public function isRedirect()
    {
        return isset($this->getData()['redirect']);
    }

    public function getRedirectUrl()
    {
        if($this->isRedirect()) {
            return $this->getData()['redirect']['url'];
        }
        return '';
    }

    public function getRedirectData()
    {
        if($this->isRedirect()) {

            $parameters = [];
            foreach($this->getData()['redirect']['parameters'] as $param) {
                $parameters[$param['name']] = $param['value'];
            }
            return $parameters;
        }
        return [];
    }

    public function getRedirectMethod()
    {
        if($this->isRedirect()) {
            if(isset($this->getData()['redirect']['method']) && strlen($this->getData()['redirect']['method']) > 0) {
                return $this->getData()['redirect']['method'];
            }
            return 'POST';
        }
        return '';
    }

    public function getTransactionReference()
    {
        return $this->data['id'];
    }
}