<?php

namespace Mannion007\BestInvestments\Sales\Domain;

interface ClientRepositoryInterface
{
    public function getByClientId(ClientId $clientId);
    public function save(Client $client);
}
