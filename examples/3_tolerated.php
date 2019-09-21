<?php

/**
 * can work on throttled internet connections, makes 2 tries with 2 seconds between last and next try.
 */

set_time_limit(0);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Tolerance\Operation\Callback as CallbackOperation;
use Tolerance\Operation\ExceptionCatcher\ThrowableCatcherVoter;
use Tolerance\Operation\Runner\{ CallbackOperationRunner, RetryOperationRunner };
use Tolerance\Waiter\{ NullWaiter, CountLimited, TimeOut };

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
$operation = new CallbackOperation(function() use ($client, $ownTIN, $targetTIN, $locale) {
    return $client->verify(new \Someson\TIN\Params([
        'UstId_1' => $ownTIN,
        'UstId_2' => $targetTIN,
    ]), $locale);
});
$exceptionCatcherVoter = new class() implements ThrowableCatcherVoter {
    public function shouldCatch(\Exception $e) {
        return true;
    }
    public function shouldCatchThrowable($e): bool {
        return true;
    }
};
$runner = new RetryOperationRunner(
    new CallbackOperationRunner(),
    new CountLimited(new TimeOut(new NullWaiter(), $timeout = 2 /* seconds */), $maxTries = 2),
    $exceptionCatcherVoter
);
try {
    $result = $runner->run($operation);
    echo $result->isValid() ? 'Request succeed: ' : 'Request failed: ';
    echo $result->getMessage() . PHP_EOL;
} catch (\Exception $e) {
    echo $e->getMessage();
}
