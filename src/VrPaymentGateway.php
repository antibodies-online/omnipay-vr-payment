<?php


namespace Omnipay\VrPayment;


use Omnipay\Common\AbstractGateway;

class VrPaymentGateway extends AbstractGateway
{

    const ENDPOINT_TEST = 'https://test.vr-pay-ecommerce.de';
    const ENDPOINT_LIVE = 'https://vr-pay-ecommerce.de';

    public function authorize(array $parameters = array())
    {
        return $this->createRequest(Message\AuthorizeRequest::class, $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(Message\PurchaseRequest::class, $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest(Message\CaptureRequest::class, $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest(Message\RefundRequest::class, $parameters);
    }

    public function getDefaultParameters()
    {
        if(!$this->getTestMode()) {
            return [
                'endpoint' => self::ENDPOINT_LIVE
            ];
        } else {
            return [
                'endpoint' => self::ENDPOINT_TEST
            ];
        }
    }

    public function getName()
    {
        return 'VR Payment Copy and Pay';
    }

    /**
     * Helper for generating data needed in a credit card tokenisation form.
     */
    public function creditCardCheck(array $parameters = array())
    {
        return $this->createRequest(Message\CreditCardCheckRequest::class, $parameters);
    }

    public function creditCardCheckStatus(array $parameters = array())
    {
        return $this->createRequest(Message\CreditCardCheckStatusRequest::class, $parameters);
    }

    public function getEndpoint() {
        return $this->getParameter('endpoint');
    }

    public function setEndpoint($endpoint) {
        return $this->setParameter('endpoint', $endpoint);
    }

    public function getEntityId() {
        return $this->getParameter('entityId');
    }

    public function setEntityId($entityId) {
        return $this->setParameter('entityId', $entityId);
    }

    public function getAccessToken() {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($accessToken) {
        $this->setParameter('accessToken', $accessToken);
    }
}