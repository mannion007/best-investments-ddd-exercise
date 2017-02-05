Feature:
  In order recommend a Specialist to a Client's Analyst
  As a Research Manager
  I need to be able to mark a specialist as approved by the Client's Compliance Officer

  Scenario: Approving a Specialist for a Project
    Given I have an active Project
    And The project has an un-vetted Specialist
    When I approve the Specialist
    Then The Specialist should be marked as approved
    And The Project Management team should be notified that the Specialist has been approved