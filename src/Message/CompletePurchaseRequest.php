<?php

namespace Omnipay\VrPayment\Message;

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
     * Set up the base data for a purchase request
     *
     * @return mixed[]
     */
    public function getData()
    {
        $data = parent::getData();
        unset($data['testMode']);
        return $data;
    }

    protected function getMethod() {
        return 'GET';
    }

    /**
     *
     */
    protected function createResponse($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}