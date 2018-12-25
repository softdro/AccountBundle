<?php

namespace App\Acme\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BatchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('first_cr')
            ->add('second_cr')
            ->add('first_account')
            ->add('second_account')
            ->add('ref_batch')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Acme\AccountBundle\Entity\Batch'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'acme_accountbundle_batch';
    }
}
