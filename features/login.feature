Feature: Login
  In order to have access to all application features and use them
  As a guest user
  I need to be able to login

  Background:
    Given I am on "/"

  Scenario: As a guest user, I want to login into my account
    When I follow "Login"
    Then I should be on "/login"
    And I should see "Login"
    And I should see "Forgot your password?"
    When I fill in "username" with "student-1"
    And I fill in "password" with "12345"
    And I press "Log in"
    Then I should be on "/"
    And I should see "Edu Admission"
