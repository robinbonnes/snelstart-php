<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

$bearerToken = new \SnelstartPHP\Secure\BearerToken\ClientKeyBearerToken($clientKey);
$accessTokenConnection = new \SnelstartPHP\Secure\AccessTokenConnection($bearerToken);
$accessToken = $accessTokenConnection->getToken();

$connection = new \SnelstartPHP\Secure\V2Connector(
    new \SnelstartPHP\Secure\ApiSubscriptionKey($primaryKey, $secondaryKey),
    $accessToken
);

$verkoopordersjabloonConnector = new \SnelstartPHP\Connector\VerkoopordersjabloonConnector($connection);

foreach ($verkoopordersjabloonConnector->findAll() as $verkoopordersjabloon) {
    var_dump($verkoopordersjabloon);
}