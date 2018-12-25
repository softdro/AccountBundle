<?php

namespace SDRO\AccountBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use SDRO\CoreBundle\Model\Type;
use SDRO\CoreBundle\Model\Status;

//use Sonata\AdminBundle\Admin\AbstractAdmin; // sort

class AccountsAdmin extends Admin {

    protected $baseRouteName = 'admin_acme_account_accounts';
    protected $parentAssociationMapping = 'accounts';
    protected $baseRoutePattern = 'accounts';

//    protected $datagridValues = array(
//        // display the first page (default = 1)
//        '_page' => 1,
//        // reverse order (default = 'ASC')
////        '_sort_order' => 'DESC',
//        // name of the ordered field (default = the model's id field, if any)
//        '_sort_by' => 'account_group',
//    );

    protected function configureRoutes(RouteCollection $collection) {
        $collection->remove('create');
        $collection->add('info', 'info');
        $collection->add('payment', 'payment');
        $collection->add('transfer', 'transfer');
        $collection->add('lists', 'lists');
        $collection->add('ajaxlists', 'ajaxlists');
        $collection->add('investment');
        $collection->add('ajaxInvestment', 'ajaxInvestment/{id}');
        $collection->remove('batch');
//        $collection->add('ajaxInvestment', $this->getRouterIdParameter() .'/{id}', [], [], [], '', ['http'], ['GET', 'POST']);
   
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('General', array('class' => 'col-md-6'))
                ->add('title', null, array('attr' => array('placeholder' => 'Account Title')))
                ->add('account_head', 'sonata_type_model', array(
                    'required' => FALSE,
                    'btn_add' => FALSE,
                    'empty_value' => '-- Select One--'
                ))

//                ->add('account_group', 'sonata_type_model', array(
////                    'query' => $query,
//                    'expanded' => true,
//                    'class' => 'AcmeAccountBundle:AccountGroup',
//                    'required' => FALSE,
//                    'multiple' => true,
//                    'by_reference' => false,
//                    'btn_add' => false,
////                    'targetEntity' => 'SDRO\AccountBundle\Entity\Account',
////                    'admin_code' => 'acme.account.admin.account',
//                    'empty_value' => '-- select one --',
//                ))
                ->add('code')
//                ->add('parent', 'sonata_type_model',array( 'targetEntity' => 'SDRO\AccountBundle\Entity\Account','admin_code' => 'acme.account.admin.accounts'))
                ->add('isAsset')
                ->add('enable')
                ->end()
                ->with('Account Group', array('class' => 'col-md-6'))
//                ->add('account_group', 'sonata_type_model', array(
////                    'required' => false,
//                    'expanded' => true,
////                    'compound' => true,
//                    'multiple' => true,
//                    'by_reference' => false,
//                    'class' => 'AcmeAccountBundle:AccountGroup',
////                    'validation_groups' => false,
////                    'query' => $query,
//                        )
////                        , array('admin_code' => 'acme.directory.admin.group',)
//                )
                ->add('account_group', 'sonata_type_collection', array(
                    'by_reference' => false,
                        ), array(
                    'edit' => 'inline',
//                    'sortable' => 'pos',
                    'inline' => 'table',
                ))
                ->end()


        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('title', null, array('label' => "Account", 'show_filter' => true))
                ->add('account_head', null, array('show_filter' => true))
                ->add('account_group', null, array('show_filter' => true))
//                ->add('group', null, array('show_filter' => true))
//                ->add('group', null, array(), 'entity', array(
//                    'class' => 'AcmeAccountBundle:Group',
//                    'property' => 'title',
//                ))
//                ->add('group', null, array(
//                    'admin_code' => 'acme.account.admin.group',
//                    'field_type' => 'entity',
////                    'field_options' => array(
////                        'query_builder' => function (\Doctrine\ORM\EntityRepository $rep) {
////                            return $rep->createQueryBuilder('a')
////                                    ->andWhere("a.account_group = :t")
////                                    ->setParameter('t', Type::PERSON_TYPE_EMPLOYEE);
////                            ;
////                        })
//                ))
                ->add('code')
                ->add('balance')
                ->add('isAsset', null, array('editable' => true))
                ->add('enable')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->add('code')
                ->addIdentifier('title')
                ->add('account_head')
                ->add('account_group')
                ->add('group', null, array(
                    'route' => array('name' => 'show'),
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'id'), // property name of entity State
                    'sort_parent_association_mappings' => array(array('fieldName' => 'group')) // property state of entity City
                ))
//                ->add('group', 'sonata_type_collection', array(
//                    'by_reference' => false
//                        ), array(
//                    'edit' => 'inline',
//                    'inline' => 'table'
//                        )  )
                ->add('balance')
                ->add('parent', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->add('isAsset', null, array('editable' => true))
                ->add('enable', null, array('editable' => true))
                ->add('_action', 'actions', array(
                    'actions' => array(
//                        'show' => array(),
                        'edit' => array(),
//                        'ledger' => array(),
//                        'delete' => array(),
                    )
                ))
        ;
    }

}
