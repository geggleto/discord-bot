<?php

namespace DiscordBot\Handlers;

class ErrorResponse extends CommandResponse
{
    public function jsonSerialize(): array
    {
        return [
            'code' => 500,
            'message' => 'Command Failed'
        ];
    }
}