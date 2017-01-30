<?php

namespace EB\AdminBundle\Form\Type;

use EB\CoreBundle\Entity\Admission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('closedAt')
            ->add('budgetFinancedNo')
            ->add('feePayerNo')
            ->add('budgetFeeThreshold')
            ->add('feeRejectedThreshold')
            ->add('status', ChoiceType::class, [
                'choices' => Admission::$statusArr,
            ])
            ->add('school')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Admission::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eb_admin_admission';
    }
}
