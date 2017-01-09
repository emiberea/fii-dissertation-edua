<?php

namespace EB\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class StudentUserAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('id')
            ->add('firstName', 'text')
            ->add('lastName', 'text')
            ->add('fatherInitial')
            ->add('pin')
            ->add('city')
            ->add('address')
            ->add('highSchool')
            ->add('specialization')
            ->add('admissionExamGrade')
            ->add('baccalaureateAverageGrade')
            ->add('baccalaureateMaximumGrade')
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('firstName')
            ->add('lastName')
            ->add('fatherInitial')
            ->add('pin')
            ->add('city')
            ->add('address')
            ->add('highSchool')
            ->add('specialization')
            ->add('admissionExamGrade')
            ->add('baccalaureateAverageGrade')
            ->add('baccalaureateMaximumGrade')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('firstName')
            ->addIdentifier('lastName')
            ->addIdentifier('fatherInitial')
            ->addIdentifier('pin')
            ->addIdentifier('city')
            ->addIdentifier('address')
            ->addIdentifier('highSchool')
            ->addIdentifier('specialization')
            ->addIdentifier('admissionExamGrade')
            ->addIdentifier('baccalaureateAverageGrade')
            ->addIdentifier('baccalaureateMaximumGrade')
        ;
    }
}
