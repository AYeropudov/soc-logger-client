<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.08.18
 * Time: 11:03
 */

namespace Productors\LoggerClient;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;


/**
 * Class ClientRabbit
 * @package Productors\LoggerClient
 */
class ClientRabbit implements ClientInterface
{
    /** @var AMQPStreamConnection */
    private $connection;
    /** @var string */
    private $queue;
    /** @var AMQPChannel */
    private $channel;

    const EXCHANGE = 'router';
    /**
     * ClientRabbit constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $clientConfig = $config['client'];

        $loggerClass = $config['loggerClass'];
        extract($clientConfig['connection']);
        $this->connection = new $clientConfig['class']($host, $port, $login, $password, $vhost);
        $this->queue = $config['appName'];
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue, false, true, false, false);
        $this->channel->exchange_declare(self::EXCHANGE, 'direct', false, true, false);
        $this->channel->queue_bind($this->queue, self::EXCHANGE);
    }

    public function send(array $message)
    {
        $message = new \PhpAmqpLib\Message\AMQPMessage(json_encode($message), array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->channel->basic_publish($message, self::EXCHANGE);
        $this->channel->close();
        $this->connection->close();
    }

}