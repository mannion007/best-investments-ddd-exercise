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

    public function it_does_not_initialize_with_a_negative_amount()
    {
        $this->beConstructedWith(-100, Currency::gbp());
        $this->shouldThrow(new \InvalidArgumentException('Cannot create a negative amount of money'))
            ->duringInstantiation();
    }

    public function it_adds()
    {
        $this->add(new Money(45, Currency::gbp()))->__toString()->shouldBe('GBP 1.45');
    }

    public function it_subtracts()
    {
        $this->subtract(new Money(45, Currency::gbp()))->__toString()->shouldBe('GBP .55');
    }

    public function it_does_not_subtract_an_amount_that_would_make_it_negative()
    {
        $this->shouldThrow(
            new \InvalidArgumentException('Cannot subtract an amount that would result in a negative amount of money')
        )->during('subtract', [new Money(101, Currency::gbp())]);
    }

    public function it_is_more_than_when_compared_to_a_lesser_amount()
    {
        $this->shouldBeMoreThan(new Money(50, Currency::gbp()));
    }

    public function it_is_not_more_than_when_compared_to_a_greater_amount()
    {
        $this->shouldNotBeMoreThan(new Money(500, Currency::gbp()));
    }

    public function it_is_less_than_when_compared_to_a_greater_amount()
    {
        $this->shouldBeLessThan(new Money(500, Currency::gbp()));
    }

    public function it_is_not_less_than_when_compared_to_a_lesser_amount()
    {
        $this->shouldNotBeLessThan(new Money(50, Currency::gbp()));
    }
}
