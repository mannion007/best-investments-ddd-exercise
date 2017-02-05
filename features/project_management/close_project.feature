Feature:
  As a Research Manager
  I want to be able to close Projects
  So that Projects can be invoiced

  Scenario: Closing a Project the Client has finished with
    Given I have an active Project
    When I close the Project
    Then The Project should be marked as closed
    And The Invoicing Team should be notified that the Project has closed