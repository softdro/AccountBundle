<?php

namespace SDRO\AccountBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use SDRO\CoreBundle\Model\Type;

class InvoiceAdmin extends Admin {

//    protected $parentAssociationMapping = 'account_details';
    protected $baseRouteName = 'invoice';
    protected $baseRoutePattern = 'invoice';

    public function configure() {
        parent::configure();
        $this->datagridValues['_sort_by'] = 'id';
        $this->datagridValues['_sort_order'] = 'DESC';
    }

    protected function configureRoutes(RouteCollection $collection) {
//        $collection->add('info_active');
        $collection->remove('edit');
        $collection->remove('create');
//        $collection->add('transfer');
//        $collection->add('expenditure');
//        $collection->add('wages');
//        $collection->add('info', 'info');
//        $collection->add('create', 'info');
//        $collection->add('info', 'create');
//        $collection->add('show');
//        $collection->add('create1', 'create1');
//        $collection->add('update', $this->getRouterIdParameter() . '/update');
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $em = $this->getConfigurationPool()->getContainer()->get('Doctrine')->getManager();

        $query = $em->createQueryBuilder('a')
                ->select('a')
                ->from('SDROAccountBundle:Account', 'a')
//                ->where('a.type = :type')
//                ->setParameter('type', Type::ACCOUNT_TYPE_FB)
        ;

        $formMapper
//                ->add('account', 'entity', array(
//                    'class' => 'SDRO\AccountBundle\Entity\Account',), array('admin_code' => 'acme.account.admin.account'))
//                ->add('account', 'sonata_type_model', array(
//                    'query' => $query,
//                    'required' => true,
//                    'targetEntity' => 'SDRO\AccountBundle\Entity\Account',
//                    'admin_code' => 'acme.person.admin.account'
////                    'empty_value' => '-- select one --',
//                ))
//                ->add('account', null, array('required' => true, 'label' => true, 'empty_value' => '-- Account Name --'))
//                ->add('user_payment')
                ->add('title', null, array('label'=>'Invoice Number','required' => true, 'attr' => array('placeholder' => 'Invoice Number')))
                ->add('note', null, array('required' => false))
                ->add('discount')
                ->add('discount_type','choice',array('choices'=>  SDRO\CoreBundle\Model\Type::getDiscountTypes()))
                ->add('vat')
                ->add('amount')
                ->add('due')
                ->add('created')
                ->add('status')
//                ->add('account','sonata_type_admin')
                ->add('sales')
//                ->add('credit', null, array('required' => false, 'label' => 'Credit', 'attr' => array('placeholder' => 'Credit')))
//                ->add('balance', null, array('label' => 'Balance'))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
//                ->add('account.type', 'doctrine_orm_choice', array(
//                    'field_options' => array(
//                        'required' => false,
//                        'choices' => Type::getAccountType()
//                    ),
//                    'field_type' => 'choice'
//                ))
//                ->add('account', null, array('admin_code' => 'acme.account.admin.account'))
//                ->add('transaction_type', 'doctrine_orm_choice', array(
//                    'field_options' => array(
//                        'required' => false,
//                        'choices' => Type::getTransactionType()
//                    ),
//                    'field_type' => 'choice'
//                ))
//                ->add('user_payment')
//                ->add('description')
                ->add('account', null, array('admin_code' => 'app.acme.account.admin.account', 'show_filter' => true,))
                ->add('title')
                ->add('note')
                ->add('discount')
                ->add('vat')
                ->add('amount')
                ->add('due')
                ->add('created', 'doctrine_orm_date_range', array(
                    'show_filter' => true,
                    'attr' => array('help' => 'Land position'),
                    'widget' => 'single_text',
                    'field_type' => 'sonata_type_date_range_picker',
                    'field_options' => array(
                        'field_options' => array('format' => 'yyyy-MM-dd'))))

        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('account', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->addIdentifier('title', null, array('label' => 'Invoice'))
                ->add('note')
                ->add('discount')
                ->add('vat')
                ->add('amount')
                ->add('due')
                ->add('created')
//                ->add('_action', 'actions', array(
//                    'actions' => array(
//                        'show' => array(),
////                        'edit' => array(),
////                        'delete' => array(),
//                    )
//                ))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureInfoFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('account.title')
//                ->add('date')
//                ->add('transaction_type')
//                ->add('expenditure_type')
                ->add('description')
                ->add('debit')
                ->add('credit')
                ->add('balance')
//                ->add('status')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'show' => array(),
//                        'edit' => array(),
//                        'delete' => array(),
                    )
                ))
        ;
    }

    /**
     * @return array
     */
    public function getBatchActions() {
        $actions = parent::getBatchActions();

        $actions['enabled'] = array(
            'label' => $this->trans('batch_enable_comments'),
            'ask_confirmation' => true,
        );

        $actions['disabled'] = array(
            'label' => $this->trans('batch_disable_comments'),
            'ask_confirmation' => false
        );

        return $actions;
    }

}
