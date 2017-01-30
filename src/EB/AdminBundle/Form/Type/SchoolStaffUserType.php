<?php

namespace EB\AdminBundle\Form\Type;

use EB\UserBundle\Entity\AbstractUser;
use EB\UserBundle\Entity\SchoolStaffUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolStaffUserType extends AbstractType
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
            ->add('title')
            ->add('jobTitle')
            ->add('academicDegree')
            ->add('school')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SchoolStaffUser::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eb_admin_ssu';
    }
}
