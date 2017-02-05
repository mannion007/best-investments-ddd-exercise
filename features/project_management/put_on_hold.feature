Feature:
  As a Sales Manager
  I want to be able to put a Client's active projects on hold
  So that the Client’s service can be suspended at their request

  Scenario: Putting an active Project on hold
    Given I have an active Project
    When I put the Project on hold
    Then The Project should be marked as on hold