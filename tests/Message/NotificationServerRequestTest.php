<?php


namespace Omnipay\VrPayment\Message;

use Omnipay\Tests\TestCase;
use Omnipay\VrPayment\VrPaymentGateway;
use ReflectionObject;
use Symfony\Component\HttpFoundation\Request;

class NotificationServerRequestTest extends TestCase
{
    private $content;
    private $key = 'MY_KEY';
    private $iv;
    private $authTag = 'MYAUTH';
    private $gateway;

    protected function setUp()
    {
        $json = $this->loadRequestJson();
        $this->content = json_decode($json, true);

        $this->iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($json, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $this->iv, $this->authTag);

        $request = new Request([], [], [], [], [], ['HTTP_X-Initialization-Vector' => bin2hex($this->iv), 'HTTP_X-Authentication-Tag' => bin2hex($this->authTag)], bin2hex($encrypted));
        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $request);
        $this->gateway->setNotificationDecryptionKey(bin2hex($this->key));
    }

    public function testIsValid()
    {
        $notification = $this->gateway->acceptNotification();
        $this->assertTrue($notification->isValid());
    }

    public function testIsValidReturnsFalse()
    {
        $notification = new NotificationServerRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->assertFalse($notification->isValid());
    }

    public function testGetTransactionStatus()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame(NotificationServerRequest::STATUS_COMPLETED, $notification->getTransactionStatus());
    }

    public function testGetTransactionStatusReturnsPending()
    {
        $json = $this->loadRequestJson();
        $json = str_replace('000.100.110', '000.200.110', $json);
        $encrypted = openssl_encrypt($json, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $this->iv, $this->authTag);

        $request = new Request([], [], [], [], [], ['HTTP_X-Initialization-Vector' => bin2hex($this->iv), 'HTTP_X-Authentication-Tag' => bin2hex($this->authTag)], bin2hex($encrypted));
        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $request);
        $this->gateway->setNotificationDecryptionKey(bin2hex($this->key));
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame(NotificationServerRequest::STATUS_PENDING, $notification->getTransactionStatus());
    }

    public function testGetTransactionStatusReturnsFailed()
    {
        $json = $this->loadRequestJson();
        $json = str_replace('000.100.110', '', $json);
        $encrypted = openssl_encrypt($json, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $this->iv, $this->authTag);

        $request = new Request([], [], [], [], [], ['HTTP_X-Initialization-Vector' => bin2hex($this->iv), 'HTTP_X-Authentication-Tag' => bin2hex($this->authTag)], bin2hex($encrypted));
        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $request);
        $this->gateway->setNotificationDecryptionKey(bin2hex($this->key));
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame(NotificationServerRequest::STATUS_FAILED, $notification->getTransactionStatus());
    }

    public function testIsRegistrationReturnsFalse()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertFalse($notification->isRegistration());
    }

    public function testIsStatusFailedReturnsFalse()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertFalse($notification->isStatusFailed());
    }

    public function testIsStatusFailedReturnsTrue()
    {
        $json = $this->loadRequestJson();
        $json = str_replace('000.100.110', '000.400.030', $json);
        $encrypted = openssl_encrypt($json, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $this->iv, $this->authTag);

        $request = new Request([], [], [], [], [], ['HTTP_X-Initialization-Vector' => bin2hex($this->iv), 'HTTP_X-Authentication-Tag' => bin2hex($this->authTag)], bin2hex($encrypted));
        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $request);
        $this->gateway->setNotificationDecryptionKey(bin2hex($this->key));
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertTrue($notification->isStatusFailed());
    }

    public function testIsStatusPendingReturnsFalse()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertFalse($notification->isStatusPending());
    }

    public function testIsStatusPendingReturnsTrue()
    {
        $json = $this->loadRequestJson();
        $json = str_replace('000.100.110', '000.200.000', $json);
        $encrypted = openssl_encrypt($json, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $this->iv, $this->authTag);

        $request = new Request([], [], [], [], [], ['HTTP_X-Initialization-Vector' => bin2hex($this->iv), 'HTTP_X-Authentication-Tag' => bin2hex($this->authTag)], bin2hex($encrypted));
        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $request);
        $this->gateway->setNotificationDecryptionKey(bin2hex($this->key));
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertTrue($notification->isStatusPending());
    }

    public function testGetMessage()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('Request successfully processed in \'Merchant in Integrator Test Mode\'', $notification->getMessage());
    }

    public function testSendData()
    {
        $notification = $this->gateway->acceptNotification();
        $this->assertSame('Omnipay\VrPayment\Message\NotificationServerResponse', get_class($notification->sendData($notification->getData())));
    }

    public function testGetTransactionId()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('1337', $notification->getTransactionId());
    }

    public function testGetTransactionReference()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('8a829449515d198b01517d5601df5584', $notification->getTransactionReference());
    }

    public function testGetAmount()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('92.00', $notification->getAmount());
    }

    public function testGetCurrency()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('EUR', $notification->getCurrency());
    }

    public function testGetCardType()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('VISA', $notification->getCardType());
    }

    public function testGetNotificationType()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('PAYMENT', $notification->getNotificationType());
    }

    public function testGetResultCode()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('000.100.110', $notification->getResultCode());
    }

    public function testGetResultDescription()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('Request successfully processed in \'Merchant in Integrator Test Mode\'', $notification->getResultDescription());
    }

    public function testGetTxStatus()
    {
        $notification = $this->gateway->acceptNotification();
        $notification->send();
        $this->assertSame('PA', $notification->getTxStatus());
    }

    public function testGetData()
    {
        $notification = $this->gateway->acceptNotification();
        $this->assertSame($this->content, $notification->getData());
    }

    private function loadRequestJson() 
    {
        $ref = new ReflectionObject($this);
        $dir = dirname($ref->getFileName());
        return file_get_contents(dirname($dir). '/Mock/NotificationServerJson.txt');
    }
}