Feature: Student register
  In order to be able to attend admissions
  As a student user
  I need to be able to register a student account

  Background:
    Given I am on "/"

  Scenario: As a student, I want to register a new student account
    When I follow "Register"
    Then I should be on "/register/student"
    And I should see "Student Register"
    And I should see "Terms and Conditions"
    When I fill in "eb_user_registration_student_email" with "emi.berea+student-102@gmail.com"
    And I fill in "eb_user_registration_student_plainPassword_first" with "12345"
    And I fill in "eb_user_registration_student_plainPassword_second" with "12345"
    And I fill in "eb_user_registration_student_firstName" with "StudentFn102"
    And I fill in "eb_user_registration_student_lastName" with "StudentLn102"
    And I check "Terms and Conditions"
    And I press "Register"
    Then I should be on "/register/check-email"
    And I should see "The user has been created successfully"
    And I should see "An email has been sent to emi.berea+student-102@gmail.com"
    When I follow registration link "emi.berea+student-102@gmail.com"
    Then I should be on "/register/confirmed"
    And I should see "Congrats emi.berea+student-102@gmail.com, your account is now activated."
    When I follow "Edu Admission"
    Then I should be on "/"
    And I should see "emi.berea+student-102@gmail.com"
