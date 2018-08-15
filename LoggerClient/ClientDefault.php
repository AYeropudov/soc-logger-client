<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.08.18
 * Time: 15:38
 */

namespace Productors\LoggerClient;


class ClientDefault implements ClientInterface
{

    public function send(array $message)
    {
        $handler = new \Monolog\Handler\RotatingFileHandler(
            __DIR__ . '/../../../../data/logger.log',
            30,
            \Monolog\Logger::DEBUG,
            false
        );
        $handler->setFormatter(new \Monolog\Formatter\NormalizerFormatter());
        $logger = new \Monolog\Logger('default.logger');
        $logger->pushHandler($handler);
        $logger->debug($message['message'], $message);
    }
}