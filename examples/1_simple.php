<?php

set_time_limit(0);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Fill these two variables:
 */
$ownTIN = '';
$targetTIN = '';

if (! $ownTIN) {
    exit('Own TIN not set.');
}
if (! $targetTIN) {
    exit('Target TIN not set.');
}

$locale = \Locale::getDefault() ?: 'de';
if (\strlen($locale) > 2) {
    $locale = strtolower(substr($locale, 0, 2));
}

$client = new \Someson\TIN\Client();
$response = $client->verify(new \Someson\TIN\Params([
    'UstId_1' => $ownTIN,
    'UstId_2' => $targetTIN,
]), $locale);

echo $response->isValid() ? 'Request succeed: ' : 'Request failed: ';
echo $response->getMessage() . PHP_EOL;
