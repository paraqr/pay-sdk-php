<?php

require_once __DIR__ . "/autoload.php";

use \paraqr\payment\Client;
use \paraqr\payment\base\Code;
use \paraqr\payment\base\Error;

$configs = include __DIR__ . '/config.php';
$account = Client::Account($configs);

try {
    $response = $account->query(Code::CURRENCY_TURKISH_LIRA);
    print_r($response);
} catch (Error $e) {
    print_r('Error: ' . $e->getMessage());
}