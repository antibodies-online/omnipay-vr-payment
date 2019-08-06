<?php


namespace Omnipay\VrPayment\Message;

use Omnipay\Common\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CreditCardCheckStatusRequest extends AbstractRequest
{

    private $resourcePath;

    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct($httpClient, $httpRequest);
        parse_str($httpRequest->getQueryString(), $queryStrings);
        $this->setEndpointRoute($queryStrings['resourcePath']);
    }

    protected function setEndpointRoute($path) {
        $this->resourcePath = $path;
    }

    protected function getEndpointRoute()
    {
        return $this->resourcePath;
    }

    public function getData()
    {
        return [];
    }
    /**
     *
     */
    protected function createResponse($data)
    {
        return $this->response = new CreditCardCheckStatusResponse($this, $data);
    }

    protected function getMethod()
    {
        return 'GET';
    }
}