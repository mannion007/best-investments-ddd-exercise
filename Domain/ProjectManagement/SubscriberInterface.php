<?php

namespace Mannion007\BestInvestments\Domain\ProjectManagement;

interface HandlerInterface
{
    public function handle(DomainEvent $event);
}