<?php

namespace spec\Mannion007\BestInvestments\Invoicing\Domain;

use Mannion007\BestInvestments\Invoicing\Domain\Invoice;
use Mannion007\BestInvestments\Invoicing\Domain\ClientId;
use Mannion007\BestInvestments\Invoicing\Domain\ConsultationId;
use Mannion007\BestInvestments\Invoicing\Domain\Consultation;
use Mannion007\BestInvestments\Invoicing\Domain\InvoiceStatus;
use Mannion007\BestInvestments\Invoicing\Domain\Money;
use Mannion007\BestInvestments\Invoicing\Domain\PaymentReference;
use Mannion007\BestInvestments\Invoicing\Domain\TimeIncrement;
use PhpSpec\ObjectBehavior;

/**
 * Class InvoiceSpec
 * @package spec\Mannion007\BestInvestments\Invoicing\Domain
 * @mixin Invoice
 */
class InvoiceSpec extends ObjectBehavior
{
    function let(
        Consultation $consultation,
        Money $payAsYouGoRate,
        ClientId $clientId,
        ConsultationId $consultationId,
        TimeIncrement $timeIncrement
    ) {
        $consultation->isNotBillable()->willReturn(false);
        $consultation->getClientId()->willReturn($clientId);
        $consultation->getConsultationId()->willReturn($consultationId);
        $consultation->getDuration()->willReturn($timeIncrement);
        $this->beConstructedWith($consultation, $payAsYouGoRate);
    }

    function it_does_not_initialise_with_a_consultation_that_is_not_billable(
        Consultation $consultation,
        Money $payAsYouGoRate
    ) {
        $consultation->isNotBillable()->willReturn(true);
        $this->beConstructedWith($consultation, $payAsYouGoRate);
        $this->shouldThrow(new \Exception('Consultation is not billable'))->duringInstantiation();
    }

    function it_initialises_with_a_consultation_that_is_billable()
    {
        $this->shouldNotThrow(new \Exception('Consultation is not billable'))->duringInstantiation();
    }

    function it_cannot_be_paid_when_it_is_not_outstanding(PaymentReference $paymentReference)
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), InvoiceStatus::paid());
        $this->shouldThrow(new \Exception('Invoice is not outstanding'))->during('pay', [$paymentReference]);
    }

    function it_can_be_paid_when_it_is_outstanding(PaymentReference $paymentReference)
    {
        $status = new \ReflectionProperty($this->getWrappedObject(), 'status');
        $status->setAccessible(true);
        $status->setValue($this->getWrappedObject(), InvoiceStatus::outstanding());
        $this->shouldNotThrow(new \Exception('Invoice is not outstanding'))->during('pay', [$paymentReference]);
    }
}
