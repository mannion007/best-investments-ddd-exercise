Feature:
  In order to suspend the service on the Client’s request
  As a Sales Manager
  I need to be able to put all their active projects on hold

  Scenario: Putting an active Project on hold
    Given I have an active Project
    When I put the Project on hold
    Then The Project should be marked as on hold