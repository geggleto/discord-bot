<?php
require_once __DIR__ . '/vendor/autoload.php';

use DiscordBot\ContainerFactory;
use DiscordBot\RabbitConsumer;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$cb = new ContainerFactory();
$container = $cb->buildContainer();

$consumer = $container->get(RabbitConsumer::class);

$consumer->consume($_ENV['RABBIT_QUEUE_NAME']);