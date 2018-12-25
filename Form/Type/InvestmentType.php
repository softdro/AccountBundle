<?php

namespace App\Acme\AccountBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvestmentType extends AbstractType {

    protected $previous = 55;

//    public function __construct(array $option) {
//        $this->previous = $option['previous'];
//    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('account', 'entity', array(
                    'empty_value' => '-- select one --',
                    'required' => TRUE,
                    'class' => 'AcmeAccountBundle:Account',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->innerJoin('a.account_head', 'ah')
                                ->where('ah.code IN  (:ah)')
                                ->setParameter("ah", \App\Acme\AccountBundle\Model\AccountUtil::ACCOUNT_HEAD_INVESTMENT);  //'select only account type query'
                    }))
                ->add('investment')
                ->add('percentage', null, array('required' => true, 'label' => 'Total Percentage (%)'))
                ->add('amount', null, array('required' => true, 'mapped' => false, 'label' => 'Add Amount'))
                ->add('previous', null, array('label' => 'Previous Investment', 'mapped' => false,
                    'data' => $options['empty_data'],
                ))
                ->add('payment', 'entity', array(
                    'empty_value' => '-- select one --',
                    'label' => 'Payment By',
                    'required' => TRUE,
                    'mapped' => FALSE,
                    'class' => 'AcmeAccountBundle:Account',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->innerJoin('a.account_head', 'ah')
                                ->where('ah.code IN  (:ah)')
                                ->setParameter("ah", \App\Acme\AccountBundle\Model\AccountUtil::ACCOUNT_HEAD_CASH_BANK);
                    }))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Acme\AccountBundle\Entity\ShareAccount'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'acme_accountbundle_investment';
    }

}
