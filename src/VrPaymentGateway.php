<?php


namespace Omnipay\VrPayment;


use Omnipay\Common\AbstractGateway;
use Omnipay\VrPayment\Message\NotificationServerRequest;

class VrPaymentGateway extends AbstractGateway
{

    const ENDPOINT_TEST = 'https://test.vr-pay-ecommerce.de';
    const ENDPOINT_LIVE = 'https://vr-pay-ecommerce.de';

    public function getDefaultParameters()
    {
        return [
            'testMode' => false,
            'endpoint' => self::ENDPOINT_LIVE
        ];
    }

    public function getName()
    {
        return 'VR Payment Copy and Pay';
    }

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

    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest(Message\NotificationServerRequest::class, $parameters);
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

    public function getNotificationDecryptionKey() {
        return $this->getParameter('notificationDecryptionKey');
    }

    public function setNotificationDecryptionKey($key) {
        $this->setParameter('notificationDecryptionKey', $key);
    }

    public function setTestMode($value)
    {
        if($value) {
            $this->setEndpoint(self::ENDPOINT_TEST);
        } else {
            $this->setEndpoint(self::ENDPOINT_LIVE);
        }
        return parent::setTestMode($value);
    }
}