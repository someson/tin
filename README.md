# Confirmation of foreign VAT identification numbers

[![Build Status](https://travis-ci.org/someson/tin.svg?branch=master)](https://travis-ci.org/someson/tin) ![GitHub](https://img.shields.io/github/license/someson/tin) ![PHP from Travis config](https://img.shields.io/travis/php-v/someson/tin/master)

## API Documentation

```
https://evatr.bff-online.de/eVatR/xmlrpc/
```

### API opening hours :D

German:
```
Über diese Schnittstelle können Sie sich täglich, in der Zeit zwischen 05:00 Uhr und 23:00 Uhr,
die Gültigkeit einer ausländischen Umsatzsteuer-Identifikationsnummer (USt-IdNr.) bestätigen lassen.
```

English:
```
Via this interface you can check the validity of a VAT identification numbers daily,
between 05:00 and 23:00 hours.
```

### Expenses of using an API

German:
```
Die Nutzung der Schnittstelle ist durch keine Registrierung reglementiert, die Nutzung ist kostenfrei.
```

English:
```
The use of the interface is not regulated by any registration, the use is free of charge.
```

### Use

```php
$client = new Client();
$response = $client->verify(new Params([
    'UstId_1' => 'DE12345678',
    'UstId_2' => 'AT98765432',
]));
echo $response->isValid() ? 'succeed' : 'failed';

// $message = $response->getMessage();
// $details = $response->getDetails();
```

### Tests

`$ ./vendor/bin/codecept run`

### Static analyzer

`$ ./vendor/bin/phpstan analyse src --level max`

## Useful links

* [TINs** nach Ländern](https://ec.europa.eu/taxation_customs/tin/tinByCountry.html?locale=de)
* [Online-Prüfmodul für TINs](https://ec.europa.eu/taxation_customs/tin/tinRequest.html?locale=de)
* [Für Anfragen über die offene Schnittstelle und Sammelanfragen (WSDL-Datei)](https://ec.europa.eu/taxation_customs/tin/checkTinService.wsdl)
* [Rechtlicher Hinweis](https://ec.europa.eu/taxation_customs/tin/legalNotice.html)

