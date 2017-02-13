Feature:
  As a Research Manager
  I want to be able to get new Specialists
  So that the I can push them to Clients

  Scenario: A new Specialist registers
    Given I have put a Potential Specialist on the list
    When The Potential Specialist registers
    Then The Specialist should be available for me to push to Clients