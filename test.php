<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.05.18
 * Time: 15:22
 */
require_once __DIR__ . '/vendor/autoload.php';

use Productors\LoggerClient\Logger;
$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
    '127.0.0.1',5672, 'guest', 'guest'
);
$client = new Logger($connection, 'test-logger');
$client->__invoke(['wow'=>'wow']);
