<?php
require_once __DIR__ . '/vendor/autoload.php';

use DiscordBot\RabbitConsumer;
use PhpAmqpLib\Message\AMQPMessage;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$consumer = new RabbitConsumer($_ENV['AMQP_HOST'], $_ENV['AMQP_PORT'], $_ENV['AMQP_USERNAME'], $_ENV['AMQP_PASSWORD']);

$callback = function ($req) {
    $msg = new AMQPMessage(
        "Hello! " . $req->get('correlation_id'),
        [
            'correlation_id' => $req->get('correlation_id')
        ]
    );

    $req->delivery_info['channel']->basic_publish(
        $msg,
        '',
        $req->get('reply_to')
    );
    $req->ack();
};

$consumer->consume($callback, $_ENV['RABBIT_QUEUE_NAME']);