<?php

namespace App\Acme\AccountBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use App\Acme\CoreBundle\Model\Type;
use App\Acme\CoreBundle\Model\Status;

//use Sonata\AdminBundle\Admin\AbstractAdmin; // sort

class ReportsAdmin extends Admin {

    protected $parentAssociationMapping = 'reports';
    protected $baseRoutePattern = 'reports';
    protected $datagridValues = array(
//        '_page' => 1,
//        '_sort_by' => 'account_group',
    );

    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('ajaxlists', 'ajaxlists');
        $collection->add('istatement', 'istatement');
        $collection->add('balancesheet', 'balancesheet');
        $collection->remove('batch');
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('General', array('class' => 'col-md-6'))
                ->add('account_head', 'sonata_type_model', array('required' => true,
                    'empty_value' => '-- Select One--'
                ))
                ->add('account_group', 'sonata_type_model')
                ->add('title', null, array('attr' => array('placeholder' => 'Account Title')))
                ->add('code')
                ->add('enable')
                ->end()


        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('title', null, array('label'=> "Account",'show_filter' => true))
                ->add('account_head', null, array('show_filter' => true))
                ->add('account_group', null, array('show_filter' => true))
                ->add('code')
                ->add('balance')
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
                ->add('account_group', null, array(
                    'route' => array('name' => 'show'),
                    'sortable' => true,
                    'sort_field_mapping' => array('fieldName' => 'id'), // property name of entity State
                    'sort_parent_association_mappings' => array(array('fieldName' => 'account_group')) // property state of entity City
                ))
                ->add('balance')
                ->add('enable')
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
