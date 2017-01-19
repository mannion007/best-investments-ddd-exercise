<?php

namespace Mannion007\BestInvestments\Domain\Sales;

interface ClientRepositoryInterface
{
    public function getByClientId(ClientId $clientId);
    public function save(Client $client);
}