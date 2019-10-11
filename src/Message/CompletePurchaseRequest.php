<?php

namespace Omnipay\VrPayment\Message;

use Omnipay\Common\Message\ResponseInterface;

class CompletePurchaseRequest extends AbstractRequest
{

    protected function getEndpointRoute()
    {
        return $this->getResourcePath();
    }

    protected function getId()
    {
        return $this->getParameter('id');
    }

    public function setId($id)
    {
        return $this->setParameter('id', $id);
    }

    protected function getResourcePath()
    {
        return $this->getParameter('resourcePath');
    }

    public function setResourcePath($resourcePath)
    {
        return $this->setParameter('resourcePath', $resourcePath);
    }

    /**
     * @param $data
     * @return ResponseInterface
     */
    protected function createGetRequest($data)
    {
        return $this->httpClient->request(
            $this->getMethod(),
            $this->getEndpoint() . $this->getEndpointRoute() .'?'. http_build_query($data),
            [
                "Authorization" => "Bearer " . $this->getAccessToken()
            ]
        );
    }

    protected function getMethod()
    {
        return 'GET';
    }

    public function getSimulation()
    {
        return false;
    }

    /**
     *
     */
    protected function createResponse($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}