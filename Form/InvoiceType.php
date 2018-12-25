<?php

namespace App\Acme\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoiceType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', null, array('label' => 'Invoice Number', 'required' => false))
                ->add('note')
                ->add('discount')
                ->add('discount_type', 'choice', array('choices' => App\Acme\CoreBundle\Model\Type::getDiscountTypes()))
                ->add('vat')
                ->add('amount')
                ->add('due')
                ->add('created')
                ->add('status')
                ->add('account',null,array('label'=>'invoice account'))
//                ->add('account', 'entity', array(
////                    'label' => 'Accounts',
////                    'mapped' => false,
//                    'required' => true,
//                    'empty_value' => '-- Select Account --',
//                    'class' => 'AcmeAccountBundle:Account',
//                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
//                        return $er->createQueryBuilder('a')
//                                ->leftJoin('a.account_head', 'ah')
//                                ->where('ah.code = :ah')
//                                ->setParameter("ah", \App\Acme\AccountBundle\Model\AccountUtil::ACCOUNT_HEAD_CASH_BANK)
//                        ;
//                    }))
                ->add('sales')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Acme\AccountBundle\Entity\Invoice'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'acme_accountbundle_invoice';
    }

}
