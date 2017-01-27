<?php

namespace EB\CoreBundle\Form\Type;

use EB\CoreBundle\Entity\Admission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdmissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sessionDate')
            ->add('budgetFinancedNo')
            ->add('feePayerNo')
            ->add('budgetFeeThreshold')
            ->add('feeRejectedThreshold')
            ->add('status')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Admission::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eb_core_admission';
    }
}
