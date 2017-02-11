@api
Feature:
  As a Prospecting Manager
  I want to be able to get Prospects to register
  So that we can get paid by Best Investments

  Scenario: Chasing up a Prospect
    Given I have received a Prospect
    When I chase up the Prospect
    Then The date and time of the chase up should be recorded

  Scenario: Registering a Prospect
    Given I have received a Prospect
    When The Prospect registers
    Then The Prospect should be marked as "registered"
    And The Project Management Team should be notified that the Prospect has registered

  Scenario: Stopping chasing up a Prospect that is not interested
    Given I have received a Prospect
    When I declare the Prospect as not interested
    Then The Prospect should be marked as "not interested"
    And The Project Management Team should be notified that the Prospect is not interested

  Scenario: Giving up on a Prospect that is a lost cause
    Given I have received a Prospect
    When I give up on the Prospect
    Then The Prospect should be marked as "not reachable"
    And The Project Management Team should be notified that the Prospect has been given up on