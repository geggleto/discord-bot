<?php

namespace DiscordBot\Handlers;

interface HandlerInterface
{
    public function __invoke(array $params): CommandResponse;
}