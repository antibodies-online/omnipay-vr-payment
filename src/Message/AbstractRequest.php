<?php


namespace Omnipay\VrPayment\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;

abstract class AbstractRequest extends OmnipayAbstractRequest
{

    abstract protected function getEndpointRoute();

    public function getData()
    {
        $data = array();

        $data['entityId'] = $this->getEntityId();
        return $data;
    }

    protected function getEntityId() {
        return $this->getParameter('entityId');
    }

    public function setEntityId($entityId) {
        return $this->setParameter('entityId', $entityId);
    }

    protected function getAccessToken() {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($accessToken) {
        return $this->setParameter('accessToken', $accessToken);
    }

    public function setEndpoint($endpoint) {
        return $this->setParameter('endpoint', $endpoint);
    }

    public function getEndpoint() {
        return $this->getParameter('endpoint');
    }

    /**
     * The response to sending the request is a text list of name=value pairs.
     * The output data is a mix of the sent data with the received data appended.
     */
    public function sendData($data)
    {
        if('GET' !== $this->getMethod()) {
            $httpResponse = $this->httpClient->request(
                $this->getMethod(),
                $this->getEndpoint() . $this->getEndpointRoute(),
                [
                    "Content-Type" => "application/x-www-form-urlencoded",
                    "Authorization" => "Bearer " . $this->getAccessToken()
                ],
                http_build_query($data)
            );
        } else {
            $httpResponse = $this->httpClient->request(
                $this->getMethod(),
                $this->getEndpoint() . $this->getEndpointRoute()
            );
        }

        $data = json_decode((string)$httpResponse->getBody(), true);

        return $this->createResponse($data);
    }

    protected function getMethod() {
        return 'POST';
    }

}