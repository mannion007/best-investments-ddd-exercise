<?php

namespace spec\Mannion007\BestInvestments\ProjectManagement\Domain;

use Mannion007\BestInvestments\ProjectManagement\Domain\Currency;
use Mannion007\BestInvestments\ProjectManagement\Domain\Money;
use PhpSpec\ObjectBehavior;

class MoneySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(100, Currency::gbp());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Money::class);
    }

    function it_adds()
    {
        $this->add(new Money(50, Currency::gbp()))->getAmount()->shouldBe(150);
    }

    function it_subtracts()
    {
        $this->subtract(new Money(50, Currency::gbp()))->getAmount()->shouldBe(50);
    }

    function it_is_not_more_than_when_given_a_greater_amount()
    {
        $this->isMoreThan(new Money(500000, Currency::gbp()))->shouldBe(false);
    }

    function it_is_more_than_when_given_a_lesser_amount()
    {
        $this->isMoreThan(new Money(5, Currency::gbp()))->shouldBe(true);
    }

    function it_is_not_less_than_when_given_a_lesser_amount()
    {
        $this->isLessThan(new Money(1, Currency::gbp()))->shouldBe(false);
    }

    function it_is_less_than_when_given_a_greater_amount()
    {
        $this->isLessThan(new Money(1000, Currency::gbp()))->shouldBe(true);
    }

    function it_casts_to_a_string_in_the_correct_format()
    {
        $this->__toString()->shouldBe('GBP 1.00');
    }
}
