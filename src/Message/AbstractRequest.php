<?php


namespace Omnipay\VrPayment\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends OmnipayAbstractRequest
{

    abstract protected function getEndpointRoute();

    abstract protected function createResponse($data);

    public function getData()
    {
        $data = array();

        $data['entityId'] = $this->getEntityId();

        $card = $this->getCard();
        if($card instanceof CreditCard) {
            $data['billing.city'] = $card->getBillingCity();
            $data['billing.country'] = $card->getBillingCountry();
            $data['billing.street1'] = $card->getBillingAddress1();
            $data['billing.street2'] = $card->getBillingAddress2();
            $data['billing.postcode'] = $card->getBillingPostcode();
            $data['billing.state'] = $card->getBillingState();
            $data['customer.companyName'] = $card->getBillingCompany();
            $data['customer.email'] = $card->getEmail();
            $data['customer.givenName'] = $card->getFirstName();
            $data['customer.surname'] = $card->getLastName();
        }
        return $data;
    }

    protected function getEntityId()
    {
        return $this->getParameter('entityId');
    }

    public function setEntityId($entityId)
    {
        return $this->setParameter('entityId', $entityId);
    }

    protected function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($accessToken)
    {
        return $this->setParameter('accessToken', $accessToken);
    }

    public function setEndpoint($endpoint)
    {
        return $this->setParameter('endpoint', $endpoint);
    }

    public function getEndpoint()
    {
        return $this->getParameter('endpoint');
    }

    public function setSimulation($simulation)
    {
        return $this->setParameter('simulation', $simulation);
    }

    public function getSimulation()
    {
        return $this->getParameter('simulation');
    }

    /**
     * The response to sending the request is a text list of name=value pairs.
     * The output data is a mix of the sent data with the received data appended.
     */
    public function sendData($data)
    {
        if('GET' !== $this->getMethod()) {
            $httpResponse = $this->createPostRequest($data);
        } else {
            $httpResponse = $this->createGetRequest($data);
        }

        $data = json_decode((string)$httpResponse->getBody(), true);
        $data['endpoint'] = $this->getEndpoint();

        return $this->createResponse($data);
    }

    /**
     * @param $data
     * @return ResponseInterface
     */
    protected function createPostRequest($data)
    {
        return $this->httpClient->request(
            $this->getMethod(),
            $this->getEndpoint() . $this->getEndpointRoute(),
            [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Authorization" => "Bearer " . $this->getAccessToken()
            ],
            http_build_query($data)
        );
    }

    /**
     * @param $data
     * @return ResponseInterface
     */
    protected function createGetRequest($data)
    {
        return $this->httpClient->request(
            $this->getMethod(),
            $this->getEndpoint() . $this->getEndpointRoute()
        );
    }

    protected function getMethod()
    {
        return 'POST';
    }

}