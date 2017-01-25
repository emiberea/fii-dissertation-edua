<?php

namespace EB\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationStudentType extends AbstractType
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
            ->add('terms', CheckboxType::class, array(
                'mapped' => false,
                'label' => 'Terms and Conditions',
                'constraints' => array(
                    new Assert\IsTrue(array('message' => 'In order to use our services, you must agree to our Terms and Conditions.'))
                ),
            ))
        ;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'eb_user_registration_student';
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
