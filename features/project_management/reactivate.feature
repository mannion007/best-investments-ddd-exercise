Feature:
  In order to resume the service on the Client’s request
  As a Sales Manager
  I need to be able to put reactivate all their on hold Projects

  Scenario: Reactivating an on hold Project
    Given I have an on hold Project
    When I reactivate the Project
    Then The Project should be marked as active