<?php

namespace DiscordBot\Domain\Users;

use DiscordBot\Handlers\CommandResponse;

class CreateNewUserResponse extends CommandResponse
{
    public function jsonSerialize(): array
    {
        return [
            'message' => 'User Created. Please connect your eth wallet by calling !connect'
        ];
    }
}