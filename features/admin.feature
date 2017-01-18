Feature: Admin
  In order to be able to make the application to be used by schools
  As an admin user
  I need to be able to create school staff users

  Background:
    Given I am on "/"
    When I follow "Login"
    Then I should be on "/login"
    And I should see "Login"
    And I should see "Forgot your password?"
    When I fill in "username" with "emi.berea+admin1@gmail.com"
    And I fill in "password" with "12345"
    And I press "Log in"
    Then I should be on "/admin/dashboard"
    And I should see "Sonata Admin"

  Scenario: As an admin, I want to create a school staff user
    When I go to "/admin/eb/user/schoolstaffuser/create"
    Then I should see "SchoolStaffUser"
    When I fill in "Email" with "email@email.com"
    And I fill in "First Name" with "User"
    And I fill in "Last Name" with "User"
    And I fill in "Title" with "Mr."
    And I fill in "Job Title" with "Assistant"
    And I fill in "Academic Degree" with "Assistant"
    And I check "Enabled"
    And I select "1" from "School"
    And I fill in "Password" with "12345"
    And I fill in "Repeat password" with "12345"
    And I press "Create"
    Then I should see "Item \"email@email.com\" has been successfully created."
    # TODO: add custom logic in a Sonata Admin controller to send email
    # TODO: add custom step for accessing the confirmation email and follow verification link
