<?php

namespace App\Acme\AccountBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BatchTransferType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
//                ->add('code')
                ->add('first_account', 'entity', array(
                    'empty_value' => '-- select one --',
                    'required' => TRUE,
                    'class' => 'AcmeAccountBundle:Account',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->innerJoin('a.account_head', 'ah')
                                ->where('ah.code IN  (:ah)')
                                ->setParameter("ah", \App\Acme\AccountBundle\Model\AccountUtil::ACCOUNT_HEAD_CASH_BANK);  //'select only account type query'
                    }))
                ->add('first_cr')
                ->add('first_amount', 'text', array(
                    'mapped' => false,
                    'label' => 'Amount',
                    'required' => true,
//                     'empty_data' => false,
                ))
                ->add('second_account', 'entity', array(
                    'empty_value' => '-- select one --',
                    'class' => 'AcmeAccountBundle:Account',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->innerJoin('a.account_head', 'ah')
                                ->where('ah.code IN (:ah)')
                                ->setParameter("ah", \App\Acme\AccountBundle\Model\AccountUtil::ACCOUNT_HEAD_CASH_BANK);  //'select only account type query'
                    }))
                ->add('second_cr')
                ->add('description', 'text', array(
                    'mapped' => false,
                    'label' => 'Description',
                    'required' => false,
//                     'empty_data' => false,
                ))
                ->add('ref_batch')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Acme\AccountBundle\Entity\Batch'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'acme_accountbundle_batch';
    }

}
