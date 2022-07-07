<?php

namespace DiscordBot;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitConsumer
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function __construct(string $host = '', int $port = 0, string $username = '', string $password = '') {
        $this->connection = new AMQPStreamConnection($host, $port, $username, $password);
    }

    public function consume(callable $callback, string $queue = ''): void {
        $this->channel = $this->connection->channel();

        $this->channel->basic_qos(null, 1, null);

//        $this->channel->exchange_declare($exchange, 'topic', false, true, false);

        $this->channel->queue_declare($queue, false, true, false, false);

//        $this->channel->queue_bind($queue, $exchange, $queue);

        $this->channel->basic_consume($queue, '', false, false, false, false, $callback);

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}