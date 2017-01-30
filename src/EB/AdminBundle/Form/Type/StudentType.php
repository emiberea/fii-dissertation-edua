<?php

namespace EB\AdminBundle\Form\Type;

use EB\UserBundle\Entity\AbstractUser;
use EB\UserBundle\Entity\StudentUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('title', ChoiceType::class, [
                'choices' => AbstractUser::$titleArr,
            ])
            ->add('fatherInitial')
            ->add('pin')
            ->add('city')
            ->add('address')
            ->add('highSchool')
            ->add('specialization')
            ->add('baccalaureateAverageGrade')
            ->add('baccalaureateMaximumGrade')
            ->add('enabled')
            ->add('verified')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => StudentUser::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eb_admin_student';
    }
}
