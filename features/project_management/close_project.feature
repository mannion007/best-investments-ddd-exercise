Feature:
  In order for Projects to be invoiced
  As a Research Manager
  I need to be able to close Projects
  Scenario: Closing a Project the Client has finished with
    Given I have an active Project
    When I close the Project
    Then The Project should be marked as closed
    And The Invoicing Team should be notified that the Project has closed