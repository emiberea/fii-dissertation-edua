<?php

namespace EB\CoreBundle\Form\Type;

use EB\CoreBundle\Entity\AdmissionAttendee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdmissionAttendeeType extends AbstractType
{
    const FORM_TYPE_PARTIAL = 0;
    const FORM_TYPE_FULL = 1;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['form_type'] == self::FORM_TYPE_PARTIAL) {
            $builder
                ->add('baccalaureateAverageGrade')
                ->add('baccalaureateMaximumGrade')
            ;
        } elseif ($options['form_type'] == self::FORM_TYPE_FULL) {
            /** @var AdmissionAttendee $admissionAttendee */
            $admissionAttendee = $builder->getData();

            $builder
                ->add('baccalaureateAverageGrade')
                ->add('baccalaureateMaximumGrade')
                ->add('admissionExamGrade')
                ->add('verified', null, [
                    'disabled' => $admissionAttendee->isVerified(),
                ])
                ->add('result', ChoiceType::class,[
                    'choices' => AdmissionAttendee::$resultArr,
                ])
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AdmissionAttendee::class,
            'form_type' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eb_core_admission_attendee';
    }
}
