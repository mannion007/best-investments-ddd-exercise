<?php

namespace Mannion007\BestInvestments\Invoicing\Domain;

class Invoice
{
    private $clientId;
    private $payAsYouGoRate;
    private $consultationId;
    private $consultationDuration;
    private $paymentReference;
    private $status;

    public function __construct(Consultation $consultation, PayAsYouGoRate $payAsYouGoRate)
    {
        if ($consultation->isNotBillable()) {
            throw new \Exception('Consultation is not billable');
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
            throw new \Exception('Invoice is not outstanding');
        }
        $this->paymentReference = $paymentReference;
        $this->status = InvoiceStatus::paid();
    }
}
