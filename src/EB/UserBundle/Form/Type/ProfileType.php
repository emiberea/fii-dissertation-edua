<?php

namespace EB\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // custom fields
        $builder
            ->add('firstName')
            ->add('lastName')
        ;
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
        return 'eb_user_profile';
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
