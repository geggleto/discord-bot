<?php

namespace DiscordBot\Infrastructure;

use DiscordBot\Handlers\CommandResponse;
use \RuntimeException;
use Psr\Container\ContainerInterface;

class MessageBus
{
    private array $registrations = [];
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @param string $key
     * @param string $handler This should be a class that is invokable and returns an CommandResponse
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function register(string $key, string $handler): void {
        if (array_key_exists($key, $this->registrations)) {
            throw new RuntimeException("Handler already defined for ${key}");
        }

        $this->registrations[$key] = $this->container->get($handler);
    }

    public function handle(string $commandName, array $payload): CommandResponse
    {
        $handler = $this->registrations[$commandName];
        return $handler($payload);
    }
}