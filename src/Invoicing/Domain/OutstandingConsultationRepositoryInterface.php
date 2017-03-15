<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

interface OutstandingConsultationRepositoryInterface
{
    public function getById(ConsultationId $id);
}
