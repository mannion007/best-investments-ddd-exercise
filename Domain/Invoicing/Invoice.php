<?php

namespace Mannion007\BestInvestments\Domain\Invoicing;

class Invoice
{
    private $clientId;
    private $payAsYouGoRate;
    private $consultationId;
    private $consultationDuration;
    private $paymentReference;
    private $status;

    public function __construct(Consultation $consultation, Money $payAsYouGoRate)
    {
        if ($consultation->isNotBillable()) {
            throw new \DomainException('Cannot create a Pay as you go payment for a consultation that is not billable');
        }
        $this->clientId = $consultation->getClientId();
        $this->consultationId = $consultation->getConsultationId();
        $this->payAsYouGoRate = $payAsYouGoRate;
        $this->consultationDuration = $consultation->getDuration();
        $this->status = InvoiceStatus::outstanding();
    }

    public function pay(PaymentReference $paymentReference)
    {
        if ($this->status->isNot(InvoiceStatus::OUTSTANDING)) {
            throw new \DomainException('Invoice is not outstanding');
        }
        $this->paymentReference = $paymentReference;
        $this->status = InvoiceStatus::paid();
    }

    public function getTotal()
    {
        return $this->payAsYouGoRate->getAmount() * $this->consultationDuration->inHours();
    }
}
