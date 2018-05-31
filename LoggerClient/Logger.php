<?php
namespace Productors\LoggerClient;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.05.18
 * Time: 14:57
 */

class Logger
{
    /** @var AMQPStreamConnection */
    private $connection;
    /** @var string */
    private $queue;
    /** @var AMQPChannel */
    private $channel;

    const EXCHANGE = 'router';
    /**
     * Logger constructor.
     * @param AMQPStreamConnection $connection
     * @param string $queue
     */
    public function __construct(AMQPStreamConnection $connection, $queue)
    {
        $this->connection = $connection;
        $this->queue = $queue;
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queue, false, true, false, false);
        $this->channel->exchange_declare(self::EXCHANGE, 'direct', false, true, false);
        $this->channel->queue_bind($this->queue, self::EXCHANGE);
    }

    public function __invoke($body)
    {
        $message = new \PhpAmqpLib\Message\AMQPMessage(json_encode($body), array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $this->channel->basic_publish($message, self::EXCHANGE);
        $this->channel->close();
        $this->connection->close();
    }


}