<?php

namespace DiscordBot\Handlers;

class ValidationError extends CommandResponse
{
    public function jsonSerialize(): array
    {
        return [
            'code' => 400,
            'message' => 'Incorrect Data for Command'
        ];
    }
}