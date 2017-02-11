<?php

namespace Mannion007\BestInvestments\Command;

use Symfony\Component\EventDispatcher\GenericEvent;

class Command extends GenericEvent implements CommandInterface
{
    public function getName(): string
    {
        return $this->getSubject();
    }

    public function getPayload(): array
    {
        return $this->getArguments();
    }
}
