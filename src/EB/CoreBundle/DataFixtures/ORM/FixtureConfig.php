<?php

namespace AppBundle\DataFixtures\ORM;

use EB\CoreBundle\Entity\School;

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

    /** @var array $specialisationArr */
    public static $schoolArr = [
        [
            'name' => 'Facultatea de Informatica',
            'country' => 'Romania',
            'city' => 'Iasi',
            'address' => 'str. Gen. Berthelot, no. 5',
            'type' => School::TYPE_FACULTY,
        ],
        [
            'name' => 'Facultatea de Economie si Administrare a Afacerilor',
            'country' => 'Romania',
            'city' => 'Iasi',
            'address' => 'bul. Carol 1, no. 2',
            'type' => School::TYPE_FACULTY,
        ],
        [
            'name' => 'Facultatea de Istorie',
            'country' => 'Romania',
            'city' => 'Iasi',
            'address' => 'bul. Carol 1, no. 2',
            'type' => School::TYPE_FACULTY,
        ],
        [
            'name' => 'Facultatea de Automatica si Calculatoare',
            'country' => 'Romania',
            'city' => 'Iasi',
            'address' => 'bul. Tudor Vladimirescu, no. 120',
            'type' => School::TYPE_FACULTY,
        ],
    ];
}
