<?php

namespace EB\UserBundle\Form\Type;

use EB\UserBundle\Entity\StudentUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // custom fields
        $builder
            ->remove('username')
            ->add('firstName')
            ->add('lastName')
            ->add('fatherInitial')
            ->add('pin')
            ->add('city')
            ->add('address')
            ->add('highSchool')
            ->add('specialization')
            ->add('baccalaureateAverageGrade')
            ->add('baccalaureateMaximumGrade')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StudentUser::class,
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'eb_user_student_profile';
    }

    /**
     * For Symfony 2.x
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
