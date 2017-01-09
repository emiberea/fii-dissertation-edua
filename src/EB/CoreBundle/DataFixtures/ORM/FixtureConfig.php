<?php

namespace AppBundle\DataFixtures\ORM;

final class FixtureConfig
{
    const MAX_ADMIN_USERS = 10;
    const MAX_SCHOOL_STAFF_USERS = 10;
    const MAX_STUDENTS_USERS = 10;

    /** @var array $highSchoolArr */
    public static $highSchoolArr = [
        'Highschool1',
        'Highschool2',
        'Highschool3',
    ];

    /** @var array $specialisationArr */
    public static $specialisationArr = [
        'Specialisation1',
        'Specialisation2',
        'Specialisation3',
    ];
}
