# VR Payment

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/antibodies-online/omnipay-vr-payment/master/LICENSE.md)
[![Packagist](https://img.shields.io/packagist/v/antibodies-online/omnipay-vr-payment.svg)](https://packagist.org/packages/antibodies-online/omnipay-vr-payment)
[![GitHub issues](https://img.shields.io/github/issues/antibodies-online/omnipay-vr-payment.svg)](https://github.com/antibodies-online/omnipay-vr-payment/issues)
[![PHP Composer Test](https://github.com/antibodies-online/omnipay-vr-payment/actions/workflows/php.yml/badge.svg)](https://github.com/antibodies-online/omnipay-vr-payment/actions/workflows/php.yml)


Table of Contents
=================

  * [Table of Contents](#table-of-contents)
  * [Omnipay: <a href="https://www.vr-payment.de//">VR Payment</a>](#omnipay-vr-payment)
    * [Installation](#installation)
    * [Basic Usage](#basic-usage)
        * [Gateway Background](#gateway-background)
    * [Instantiate a gateway](#instantiate)
    * [Switch between Test Modes](#switch-between-test-modes)
    * [Out of standard Functions](#out-of-standard-functions)
  
# Omnipay: [VR Payment](https://www.vr-payment.de/)

**VR Payment driver for the Omnipay PHP payment processing library**

Written to specification:

* *[TECHNICAL REFERENCE](https://vr-pay-ecommerce.docs.oppwa.com/) (2019-08-05)

This package implements VR Payment support for [OmniPay](https://github.com/thephpleague/omnipay).

![VR Payment GmbH](docs/logo.png?raw=true "VR Payment")
  
## Installation

**This is the `master` branch for the current Omnipay 3.x branch (tested against 3.0.2).**

Omnipay is installed via [Composer](http://getcomposer.org/). To install, add it
to your `composer.json` file:

```json
{
  "require": {
      "antibodies-online/omnipay-vr-payment": "~1.0"
  }
}
```

or direct from [packagist](https://packagist.org/packages/antibodies-online/omnipay-vr-payment)

    composer require "antibodies-online/omnipay-vr-payment: ~1.0"

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository. You will find more specific details can be found below.

### Gateway Background

At least there is only one gateway provided by [VR Payment](https://www.vr-payment.de/).
There's no separate gateway for frontend forms and server-to server requests.
However they splitted the tutorials on their document page.

You will most likely be using a mix of COPY+PAY, and Server-to-Server functions as they complement each other,
if you want to be SAQ-A(For more information see: [PCI](https://www.pcisecuritystandards.org/pci_security/completing_self_assessment)) compliant.

## Instantiate a gateway

To communicate with vr payment there are different mandatory informations which the gateway needs:

* AccessToken:<br>
The access token is generated by [VR Payment](https://www.vr-payment.de/) and authenticates your application against the gateway.
* EntityId:<br>
The entity id is generated by [VR Payment](https://www.vr-payment.de/) and defines the gateway you want to use (e.g. Credit Card, PayPal,...)

Now let's create the gateway:
````php
$gateway = Omnipay\Omnipay::create('VrPayment_VrPayment');
$gateway->setEntityId('xyz');
$gateway->setTestMode(true);
$gateway->setAccessToken('myAccessToken');

$request = $gateway->authorize([
    'transactionId' => $transactionId, // Merchant site ID (or a nonce for it)
    'amount' => 9.99, // Major units
    'currency' => 'EUR',
    'token' => 'creditCardToken', // This is only needed, if you are using COPY+PAY
    'card' => [ // Is not implemented yet
        ....
    ]
]);
$response = $request->send();
````    

## Switch between Test Modes
VR Payment provides two different Test Modes.
* EXTERNAL: All transactions will be forwarded to the processor's test system
* INTERNAL: All transactions will be processed by VR Payments simulator

Default: INTERNAL

You can switch the Test Mode using this command:
````php
    $gateway->setSimulation('EXTERNAL');
````

## Parsing Webhooks
There are different actions which are send via Webhook. For further documentation please read the [documentation](https://vr-pay-ecommerce.docs.oppwa.com/tutorials/webhooks/integration-guide)
Omnipay provides a standard way to accept and read such notifications.
However the returned Request object is not standardized and may differ in other implementations.

````php
$gateway = Omnipay\Omnipay::create('VrPayment_VrPayment');
$gateway->setNotificationDecryptionKey('myDecryptionKey');

$request = $gateway->acceptNotifications(); // Parses the HTTP Request
$requestArray = $request->getData();
$response = $request->sendData();
$response->sendData()->accept(); 
````    


## Out of standard functions

There are a couple of functions which are not defined in the standard of omnipay.
However I think to use COPY+PAY, it's easier to include those function in this package.

* creditCardCheck()<br>
This function calls the gateway to create a new checkout id, which is used to generate the payment form.
````html
<form action="{$YOUR_REDIRECT_URL}" class="paymentWidgets" data-brands="VISA MASTER AMEX">&nbsp;</form>
<script type="text/javascript" src="{$JAVASCRIPT_URL}"></script>
{literal}
<script>
    var wpwlOptions = {style:"card"}
</script>
{/literal}
````
````php
$gateway = Omnipay\Omnipay::create('VrPayment_VrPayment');
// Set authentication info
$request = $gateway->creditCardCheck()->send();
$javascript_url = $request->getPaymentFormJsUrl();
````
* creditCardCheckStatus()<br>
This function calls the gateway using the reference provided in the query parameters,
to query for the payment information of the creditCardCheck form result.
This function will extract all needed information out of the url by itself.
````php
$gateway = Omnipay\Omnipay::create('VrPayment_VrPayment');
$cardCheckStatusResponse = $gateway->creditCardCheckStatus()->send();
if ($cardCheckStatusResponse->isSuccessful()) {
    $token = $cardCheckStatusResponse->getTransactionReference();
}
````

## Contributing
We really appreciate if you report bugs and errors. Feel free to ask for additional functionality/fields.
But be aware that the maintainers may not implement all features. If you can provide a Pull Request
for your Features that would be amazing.
