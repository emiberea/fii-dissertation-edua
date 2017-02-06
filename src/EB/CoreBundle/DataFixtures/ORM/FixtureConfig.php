<?php

namespace EB\CoreBundle\DataFixtures\ORM;

use EB\CoreBundle\Entity\School;

final class FixtureConfig
{
    const MAX_ADMIN_USERS = 2;
    const MAX_SCHOOL_STAFF_USERS = 20;
    const MAX_STUDENTS_USERS = 80;
    const MAX_ADMISSIONS = 20;
    const MAX_ADMISSION_ATTENDEES = 120;
    const PASSWORD = '12345';

    /** @var array $highSchoolArr */
    public static $highSchoolArr = [
        'Colegiul National, Iasi',
        'Colegiul National "Costache Negruzzi", Iasi',
        'Liceul de Informatica "Grigore Moisil", Iasi',
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
            'address' => 'bul. Carol 1, no. 11',
            'type' => School::TYPE_FACULTY,
        ],
        [
            'name' => 'Facultatea de Istorie',
            'country' => 'Romania',
            'city' => 'Iasi',
            'address' => 'bul. Carol 1, no. 11',
            'type' => School::TYPE_FACULTY,
        ],
        [
            'name' => 'Facultatea de Automatica si Calculatoare',
            'country' => 'Romania',
            'city' => 'Iasi',
            'address' => 'bul. Dimitrie Mangeron 27',
            'type' => School::TYPE_FACULTY,
        ],
    ];
}
