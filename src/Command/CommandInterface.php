<?php

namespace Mannion007\BestInvestments\Command;

interface CommandInterface
{
    public function getName(): string;
    public function getPayload(): array;
}
