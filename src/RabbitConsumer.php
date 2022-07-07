<?php

namespace DiscordBot;

use DiscordBot\Infrastructure\MessageBus;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitConsumer
{
    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private MessageBus $bus;

    public function __construct(MessageBus $bus, AMQPStreamConnection $connection) {
        $this->connection = $connection;
        $this->bus = $bus;
    }

    public function consume(string $queue = ''): void {
        $this->channel = $this->connection->channel();

        $this->channel->basic_qos(null, 1, null);

//        $this->channel->exchange_declare($exchange, 'topic', false, true, false);

        $this->channel->queue_declare($queue, false, true, false, false);

//        $this->channel->queue_bind($queue, $exchange, $queue);

        $this->channel->basic_consume($queue, '', false, false, false, false, [$this, 'processor']);

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }

    public function processor($req) {
        $frame = json_decode($req->body, true);
        if (!$frame || !$frame['name']) {
            return;
        }
        try {

            $response = $this->bus->handle($frame['name'], $frame['payload'] ?? []);

            $msg = new AMQPMessage(
                json_encode($response),
                [
                    'correlation_id' => $req->get('correlation_id')
                ]
            );
        } catch (\Exception $exception) {
            $msg = new AMQPMessage(
                json_encode(["message" => "Invalid Command"]),
                [
                    'correlation_id' => $req->get('correlation_id')
                ]
            );
        }

        $req->delivery_info['channel']->basic_publish(
            $msg,
            '',
            $req->get('reply_to')
        );

        $req->ack();
    }
}