Feature:
  In order for a Consultation to take place between a Clients and Specialists
  As a Research Manager
  I need to be able schedule Consultations

  Scenario: Scheduling a Consultation
    Given I have an active Project
    And I have a Specialist
    And The Specialist is approved for the Project
    When I schedule a Consultation with the Specialist on the Project
    Then The Consultation should be scheduled with the Specialist on the Project
    And The Project Management Team should be notified that the Consultation has been scheduled
