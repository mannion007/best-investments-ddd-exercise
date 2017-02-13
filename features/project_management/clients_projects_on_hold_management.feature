Feature:
  As a Research Manager
  I want to know when a Project is on hold
  So that I do not waste time scheduling consultations for on hold Projects

  Scenario: An active Project is put on hold
    Given I have an active Project
    When The Project is put on hold
    Then The Project should be marked as on hold

  Scenario: An on hold Project is reactivated
    Given I have an on hold Project
    When The Project is reactivated
    Then The Project should be marked as active