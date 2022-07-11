<?php

namespace DiscordBot;

use DI\ContainerBuilder;
use DiscordBot\Domain\Users\CreateNewUserHandler;
use DiscordBot\Handlers\CreateHandler;
use DiscordBot\Infrastructure\MessageBus;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Setup;
use Essentials\Application\Definitions\EnvStage;
use Essentials\Application\Settings\Settings;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public function buildContainer(): ContainerInterface
    {
        $container = new ContainerBuilder();
        $container->addDefinitions([
            EntityManager::class => function (ContainerInterface $container): EntityManager
            {
                $config = ORMSetup::createAnnotationMetadataConfiguration([__DIR__ . '/Persistence'],true);

                $conn = [
                    'dbname' => $_ENV['DB_NAME'],
                    'user' => $_ENV['DB_USERNAME'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'host' => $_ENV['DB_HOST'],
                    'driver' => 'pdo_mysql',
                ];

                Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');

                // obtaining the entity manager
                return EntityManager::create($conn, $config);
            },
            AMQPStreamConnection::class => function (ContainerInterface $container): AMQPStreamConnection {
                return new AMQPStreamConnection($_ENV['AMQP_HOST'], $_ENV['AMQP_PORT'], $_ENV['AMQP_USERNAME'], $_ENV['AMQP_PASSWORD']);
            },
            MessageBus::class => function (ContainerInterface $container): MessageBus {
                $bus = new MessageBus($container);
                $bus->register('create', CreateNewUserHandler::class);
                return $bus;
            },
            Logger::class => function (ContainerInterface $container): Logger {
                $logger = new Logger('drl-bot');
                $logger->pushHandler(new StreamHandler(__DIR__.'/../logs/drl-consumer.log', Level::Warning));
                return $logger;
            },
        ]);
        return $container->build();
    }
}