<?php

namespace EB\CoreBundle\Event;

final class NotificationEvents
{
    const STUDENT_REGISTERED = 'notification.student_registered';
    const STUDENT_ATTEND_ADMISSION = 'notification.student_attend_admission';
    const SSU_CONFIRM_STUDENT = 'notification.ssu_confirm_student';
    const SSU_PRE_CLOSE_ADMISSION = 'notification.ssu_pre_close_admission';
}
