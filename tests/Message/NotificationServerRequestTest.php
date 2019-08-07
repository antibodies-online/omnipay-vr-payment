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
    private $auth_tag = 'MYAUTH';
    private $gateway;

    protected function setUp()
    {
        $json = $this->loadRequestJson();
        $this->content = json_decode($json, true);

        $this->iv = openssl_random_pseudo_bytes(16);
        if(PHP_VERSION_ID >= 70100) {
            $encrypted = openssl_encrypt($json, 'aes-256-gcm', $this->key, OPENSSL_RAW_DATA, $this->iv, $this->auth_tag);
        } else {
            $this->iv = \Sodium\randombytes_buf(\Sodium\CRYPTO_AEAD_AES256GCM_NPUBBYTES);
            $this->key = \Sodium\randombytes_buf(\Sodium\CRYPTO_AEAD_AES256GCM_NPUBBYTES);
            $encrypted = \Sodium\crypto_aead_aes256gcm_encrypt($json . $this->auth_tag, $this->iv, $this->key, '');
        }

        $request = new Request([], [], [], [], [], ['HTTP_X-Initialization-Vector' => bin2hex($this->iv), 'HTTP_X-Authentication-Tag' => bin2hex($this->auth_tag)], bin2hex($encrypted));
        $this->gateway = new VrPaymentGateway($this->getHttpClient(), $request);
        $this->gateway->setNotificationDecryptionKey(bin2hex($this->key));
    }

    public function testValidate() {
        $notification = $this->gateway->acceptNotification();
        $this->assertTrue($notification->isValid());
    }

    public function testGetData() {
        $notification = $this->gateway->acceptNotification();
        $this->assertSame($this->content, $notification->getData());
    }

    private function loadRequestJson() {
        $ref = new ReflectionObject($this);
        $dir = dirname($ref->getFileName());
        return file_get_contents(dirname($dir). '/Mock/NotificationServerJson.txt');
    }
}