<?php

namespace DiscordBot\Handlers;

abstract class CommandResponse implements \JsonSerializable
{
    abstract public function jsonSerialize(): array;
}