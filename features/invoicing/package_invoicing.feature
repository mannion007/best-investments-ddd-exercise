Feature:
  As an Invoicing Manager
  I want to raise Package Invoices
  So that our Clients know how the available time on their package was used

  Scenario: Raising a Package Invoice for an Expired Package
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'gold-2015-06-6' which is 'Expired'
    When I raise a Package Invoice for Package 'gold-2015-06-6'
    Then I should have a Package Invoice for Package 'gold-2015-06-6'
    And Package 'gold-2015-06-6' should be marked as 'Invoiced'
    And That invoice should have a total value of GBP 0

  Scenario: Raising a Package Invoice for an Invoiced Package
    Given I have Client 'ABC'
    And Client 'ABC' has Package 'silver-2015-06-6' which is marked as 'Expired'
    And I have Package Invoice 10001 for Package 'silver-2015-06-6'
    When I try to raise a Package Invoice for Package 'silver-2015-06-6'
    Then I should not have another Package Invoice for Package 'silver-2015-06-6'