Feature:
  As a Client
  I want to pay Pay as You Go Invoices for Consultations that I have had
  So that I can continue to use the Services of Best Investments without Purchasing a Package

  Scenario: Paying the whole amount remaining on a Pay As You Go Invoice
    Given I have Pay As You Go Invoice 10001 which is 'Unpaid' with a remaining amount of GBP 300
    When I make a payment of GBP 300 for Pay As You Go Invoice 10001
    Then Pay As You Go Invoice 10001 should be 'Paid'
    And Pay As You Go Invoice 10001 should have an amount of GBP 0

  Scenario: Paying part of the amount remaining on a Pay As You Go Invoice
    Given I have Pay As You Go Invoice 10001 which is 'Unpaid' with a remaining amount of GBP 300
    When I make a payment of GBP 105 for Pay As You Go Invoice 10001
    Then Pay As You Go Invoice 10001 should be 'Partially Paid'
    And Pay As You Go Invoice 10001 should have an amount of GBP 195

  Scenario: Paying exactly what’s remaining on a Pay As You Go Invoice
    Given I have Pay As You Go Invoice 10001 which is 'Partially Paid' with a remaining amount of GBP 102
    When I make a payment of GBP 102 for Pay As You Go Invoice 10001
    Then Pay As You Go Invoice 10001 should be 'Paid'
    And Pay As You Go Invoice 10001 should have an amount of GBP 0

  Scenario: Overpaying a Pay As You Go Invoice
    Given I have Pay As You Go Invoice 10001 which is 'Partially Paid' with a remaining amount of GBP 35
    When I try to make a payment of GBP 50 for Pay As You Go Invoice 10001
    Then Pay As You Go Invoice 10001 should be 'Partially Paid'
    And Pay As You Go Invoice 10001 should have an amount of GBP 35

  Scenario: Paying a Pay As You Go Invoice which is already paid
    Given I have Pay As You Go Invoice 10001 which is 'Paid'
    When I try to make a payment of GBP 5 for Pay As You Go Invoice 10001
    Then Pay As You Go Invoice 10001 should be 'Paid'
    And Pay As You Go Invoice 10001 should have an amount of GBP 35