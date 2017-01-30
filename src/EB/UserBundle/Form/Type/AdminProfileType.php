<?php

namespace EB\UserBundle\Form\Type;

use EB\UserBundle\Entity\AbstractUser;
use EB\UserBundle\Entity\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminProfileType extends AbstractType
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
            ->add('title', ChoiceType::class, [
                'choices' => AbstractUser::$titleArr,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AdminUser::class,
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
        return 'eb_user_admin_profile';
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
