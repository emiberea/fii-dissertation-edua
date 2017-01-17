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
    When I fill in "username" with "admin-1"
    And I fill in "password" with "12345"
    And I press "Log in"
    Then I should be on "/admin/dashboard"
    And I should see "Sonata Admin"

  Scenario: As an admin, I want to create a school staff user
    When I go to "/admin/eb/user/schoolstaffuser/create"
    And I should see "SchoolStaffUser"

