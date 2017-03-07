<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

interface ClientRepositoryInterface
{
    public function getById(ClientId $clientId): Client;
    public function save(Client $client);
}
