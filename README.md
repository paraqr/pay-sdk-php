# ParaQR - PosPay

#### simplest, minimal dependency

### Feature

* easy to use
* supports php-7x

### Install

```
composer require paraqr/payment
```

or add: `paraqr/payment":"^1.0"` in composer.json

### Demo

* Common Config

```php
$config = [
    'app_id'         => 'You-App-ID',               // application id
    'app_secret_key' => 'You-App-secret-key',       // application private key
    'paraqr_pub_key' => 'ParaQR-Api-Public-key',    // paraqr public key
    'gateway'        => '',                         // api service gateway 
];

```

* Request Payment

```php
$trading = WechatPay::Trading($config);
$response = $trading->webPay('trade.pay.webDirect', [
        'out_trade_no'  => '',
        'subject'       => '',
        'amount'        => 100, // positive integer required
        'notify_url'    => '',
        'return_url'    => '',
        'currency_code' => '',
        'client_ip'     => '',
]);
print_r($response);

```

* For more examples refer to the demo
* For more information, please refer to the [online documentation](https://document.paraqr.com/#/)
