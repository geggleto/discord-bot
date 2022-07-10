<?php

namespace DiscordBot\Domain\Users;

use DiscordBot\Handlers\CommandResponse;
use DiscordBot\Handlers\ErrorResponse;
use DiscordBot\Handlers\HandlerInterface;
use DiscordBot\Handlers\ValidationError;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;

class CreateNewUserHandler implements HandlerInterface
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(array $params): CommandResponse {
        if (!$params['discord_id']) {
            return new ValidationError();
        }

        try {
            $uuid = Uuid::uuid4();
            $this->entityManager->getConnection()->insert('registrations', [
                $uuid,
                $params['discord_id'],
                '',
            ]);

            return new CreateNewUserResponse();
        } catch (\Exception $exception) {
            return new ErrorResponse();
        }
    }
}