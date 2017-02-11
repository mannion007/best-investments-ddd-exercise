<?php

namespace Mannion007\BestInvestments\Command;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;
}
