Feature:
  As a Sales Manager
  I want to be able to manage a Client's account
  So that we can maintain the best possible relationship with the Client

  Scenario: Putting an active Project on hold
    Given I have an active Project
    When I put the Project on hold
    Then The Project should be marked as on hold

  Scenario: Reactivating an on hold Project
    Given I have an on hold Project
    When I reactivate the Project
    Then The Project should be marked as active