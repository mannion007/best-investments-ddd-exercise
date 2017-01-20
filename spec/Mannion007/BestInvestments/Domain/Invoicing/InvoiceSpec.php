<?php

namespace spec\Mannion007\BestInvestments\Domain\Invoicing;

use Mannion007\BestInvestments\Domain\Invoicing\Invoice;
use Mannion007\BestInvestments\Domain\Invoicing\ClientId;
use Mannion007\BestInvestments\Domain\Invoicing\ConsultationId;
use Mannion007\BestInvestments\Domain\Invoicing\Consultation;
use Mannion007\BestInvestments\Domain\Invoicing\Money;
use Mannion007\BestInvestments\Domain\Invoicing\PaymentReference;
use Mannion007\BestInvestments\Domain\Invoicing\TimeIncrement;
use PhpSpec\ObjectBehavior;

/**
 * Class InvoiceSpec
 * @package spec\Mannion007\BestInvestments\Domain\Invoicing
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
        $this->pay($paymentReference);
        $this->shouldThrow(new \Exception('Invoice is not outstanding'))->during('pay', [$paymentReference]);
    }

    function it_can_be_paid_when_it_is_outstanding(PaymentReference $paymentReference)
    {
        $this->shouldNotThrow(new \Exception('Invoice is not outstanding'))->during('pay', [$paymentReference]);
    }
}
