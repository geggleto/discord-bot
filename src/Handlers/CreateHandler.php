<?php

namespace DiscordBot\Handlers;

class CreateHandler implements HandlerInterface
{
    public function __invoke(array $params): CommandResponse {
        return new CreateResponse();
    }
}