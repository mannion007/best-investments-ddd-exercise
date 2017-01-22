<?php

namespace Mannion007\BestInvestments\Event;

interface EventInterface
{
    public function getEventName(): string;
    public function getPayload(): array;
    public function getOccurredAt(): \DateTime;
    public static function fromPayload(array $payload);
}
