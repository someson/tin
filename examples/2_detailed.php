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
$formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);
if (\strlen($locale) > 2) {
    $locale = strtolower(substr($locale, 0, 2));
}

$client = new \Someson\TIN\Client();
$response = $client->verify(new \Someson\TIN\Params([
    'UstId_1' => $ownTIN,
    'UstId_2' => $targetTIN,
    'Firmenname' => 'TestName GmbH.',
    'Ort' => 'Nürnberg',
    'PLZ' => '90409',
    'Strasse' => 'Maxfeldstr. 5'
]), $locale);

echo $response->isValid() ? 'Request succeed: ' : 'Request failed: ';
echo $response->getMessage() . PHP_EOL;

$details = $response->getDetails();
echo sprintf('Company name: %s' . PHP_EOL, $details->getCompany()->getName() ?: '[unknown]');
echo sprintf('Valid from %s till %s' . PHP_EOL,
    $details->getValidity()->from() ?: '[unknown]',
    $details->getValidity()->till() ?: '[unknown]'
);

$addr = $details->getCompany()->getAddress();
echo sprintf('Locality: %s (%s)', $addr->getLocality(), $addr->getLocalityStatus()) . PHP_EOL;
echo sprintf('Postal code: %s (%s)', $addr->getPostalCode(), $addr->getPostalCodeStatus()) . PHP_EOL;
echo sprintf('Street: %s (%s)', $addr->getStreet(), $addr->getStreetStatus()) . PHP_EOL;
