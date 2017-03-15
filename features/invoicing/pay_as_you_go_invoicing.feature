Feature:
  As an Invoicing Manager
  I want to raise Pay as You Go Invoices for Consultations that have taken place
  So that we can get payment for Consultations from Clients who do not use our Package model

  Scenario: Raising a Pay as You Go Invoice for an Outstanding Consultation
    Given I have Client 'ABC' with a fixed hourly rate of GBP 85.00
    And Client 'ABC' has an Outstanding Consultation 'project1-1' which lasted 175 minutes
    When I raise a Pay as You Go Invoice for Consultation 'project1-1'
    Then I should have a Pay as You Go Invoice for Consultation 'project1-1'
    And that invoice should have a total value of GBP 255