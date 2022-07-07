<?php

namespace DiscordBot\Handlers;

class CreateResponse extends CommandResponse
{
    public function jsonSerialize(): array
    {
        return [
            'message' => 'Command Executed'
        ];
    }
}