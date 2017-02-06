Feature:
  As a Research Manager
  I want to be able to mark Specialist as approved by Clients' Compliance Officer
  So that approved Specialists can be recommended to Clients

  Scenario: Approving a Specialist for a Project
    Given I have an active Project
    And The project has an un-vetted Specialist
    When I approve the Specialist
    Then The Specialist should be marked as approved
    And The Project Management team should be notified that the Specialist has been approved