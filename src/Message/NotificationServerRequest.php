<?php


namespace Omnipay\VrPayment\Message;


use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class NotificationServerRequest extends OmnipayAbstractRequest implements NotificationInterface
{

    const EVENT_AUTHORIZATION       = 'PA';
    const EVENT_CAPTURE             = 'CP';
    const EVENT_DEBIT               = 'DB';
    const EVENT_CREDIT              = 'CD';
    const EVENT_REVERSAL            = 'RV';
    const EVENT_REFUND              = 'RF';
    const EVENT_REGISTER            = 'RG';
    const EVENT_CONFIRMATION        = 'CF';
    const EVENT_SECURE_CHECK        = '3D';

    public function isValid()
    {
        try {
            $data = $this->getData();
            if(is_array($data) && 0 < count($data)) {
                return true;
            }
        } catch (\Exception $exception) {
            // No need to return false here, it's done at the end of this function anyway.
        }

        return false;
    }

    /**
     * Returns 0 if transaction is rejected, 0 otherwise
     *
     * @return int
     */
    public function isRejectedTransaction()
    {
        return preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $this->data['payload']['result']['code']);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        if (isset($this->data)) {
            return $this->data;
        }

        $this->data = json_decode($this->decrypt($this->httpRequest), true);

        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getTxStatus()
    {
        return $this->data['payload']['paymentType'];
    }

    /**
     * Was the transaction successful?
     *
     * @return string Transaction status, one of {@see STATUS_COMPLETED}, {@see #STATUS_PENDING},
     * or {@see #STATUS_FAILED}.
     */
    public function getTransactionStatus()
    {
        if((bool)preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $this->data['payload']['result']['code'])) {
            return static::STATUS_COMPLETED;
        }

        if ((bool)preg_match('/^(000\.200)/', $this->data['payload']['result']['code'])) {
            return static::STATUS_PENDING;
        }

        return static::STATUS_FAILED;
    }

    /**
     * Response Message
     *
     * @return string A response message from the payment gateway
     */
    public function getMessage()
    {
        return $this->data['payload']['result']['description'];
    }

    /**
     * Send the request with specified data
     *
     * @param mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->createResponse($data);
    }

    /**
     * The response is a very simple message for returning an acknowledgement to Vr Payment.
     */
    protected function createResponse($data)
    {
        return $this->response = new NotificationServerResponse($this, $data);
    }

    /**
     * The Merchant identifier
     */
    public function getTransactionId()
    {
        if(isset($this->data['payload']['merchantTransactionId'])) {
            return $this->data['payload']['merchantTransactionId'];
        }
        return null;
    }

    /**
     * The VR Payment gateway identifier.
     */
    public function getTransactionReference()
    {
        return $this->data['payload']['id'];
    }

    public function getAmount()
    {
        if(isset($this->data['payload']['presentationCurrency'])) {
            return $this->data['payload']['presentationAmount'];
        } else {
            return null;
        }
    }

    public function getCurrency()
    {
        if(isset($this->data['payload']['presentationCurrency'])) {
            return $this->data['payload']['presentationCurrency'];
        }
        return null;
    }

    public function getCardType()
    {
        return $this->data['payload']['paymentBrand'];
    }

    public function getNotificationType()
    {
        return $this->getData()['type'];
    }

    public function getResultCode()
    {
        return $this->data['payload']['result']['code'];
    }

    public function getResultDescription()
    {
        return $this->data['payload']['result']['description'];
    }

    public function getNotificationDecryptionKey()
    {
        return $this->getParameter('notificationDecryptionKey');
    }

    public function setNotificationDecryptionKey($key)
    {
        $this->setParameter('notificationDecryptionKey', $key);
    }

    private function decrypt(Request $request)
    {
        $key = hex2bin($this->getNotificationDecryptionKey());
        $iv = hex2bin($request->headers->get('X-Initialization-Vector'));
        $authTag = hex2bin($request->headers->get('X-Authentication-Tag'));
        $cipherText = hex2bin($request->getContent());

        $result = openssl_decrypt($cipherText, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $authTag);
        return $result;
    }
}