<?php
require 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

$accessKey = 'GOOG1ERAE4HESF7FXY4YQ4W55O6GWK3SAILVXWMCR6BRLIHA7TZKPXMIE6G5X';
$secretKey = 'rVlry//RniiPXygeM6SfypD8rdcHTWz1Vz/0eQ33';

$storage = new StorageClient([
    'projectId' => 'gr-prod-1',
    'keyFile' => [
        'type' => 'service_account',
        'client_email' => $accessKey,
        'private_key' => $secretKey
    ]
]);

$bucketName = 'giganticretail-logs';
$bucket = $storage->bucket($bucketName);

$rawPostData = file_get_contents('php://input');
$transaction = json_decode($rawPostData, true);

if (json_last_error() !== JSON_ERROR_NONE || empty($transaction)) {
    http_response_code(400);
    exit;
}

$filename = 'logs/transaction_' . date('Ymd_His') . '.json';
$bucket->upload(
    json_encode($transaction, JSON_PRETTY_PRINT),
    ['name' => $filename]
);

echo $filename . "\n";
