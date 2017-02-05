Feature:
  As a Sales Manager
  I want to be able to reactivate all of a Client's on hold Projects
  So that their service can be resumed at their request

  Scenario: Reactivating an on hold Project
    Given I have an on hold Project
    When I reactivate the Project
    Then The Project should be marked as active