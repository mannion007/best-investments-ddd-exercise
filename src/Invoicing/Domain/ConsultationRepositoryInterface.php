<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

interface ConsultationRepositoryInterface
{
    public function getById(ConsultationId $consultationId): Consultation;
    public function save(Consultation $consultation): void;
}
