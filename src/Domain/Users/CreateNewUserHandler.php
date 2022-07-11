<?php

namespace DiscordBot\Domain\Users;

use DiscordBot\Handlers\CommandResponse;
use DiscordBot\Handlers\ErrorResponse;
use DiscordBot\Handlers\HandlerInterface;
use DiscordBot\Handlers\ValidationError;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Ramsey\Uuid\Uuid;

class CreateNewUserHandler implements HandlerInterface
{
    private EntityManager $entityManager;
    private Logger $logger;

    public function __construct(EntityManager $entityManager, Logger $logger) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function __invoke(array $params): CommandResponse {
        if (!$params['discord_id']) {
            $this->logger->warning('Invalid Parameters', $params);
            return new ValidationError();
        }

        try {
            $uuid = Uuid::uuid4();
            $this->entityManager->getConnection()->insert('registrations', [
                'uuid' => $uuid,
                'discord_id' => $params['discord_id'],
                'eth_address' => null,
            ]);

            return new CreateNewUserResponse();
        } catch (\Exception $exception) {
            $this->logger->error('Error writing to db', [
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getCode()
            ]);

            return new ErrorResponse();
        }
    }
}