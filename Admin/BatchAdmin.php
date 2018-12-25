<?php

namespace SDRO\AccountBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use SDRO\CoreBundle\Model\Type;
use SDRO\CoreBundle\Model\Status;

class BatchAdmin extends Admin {

    protected $parentAssociationMapping = 'batch';
    protected $baseRoutePattern = 'batch';

    public function configure() {
        parent::configure();
        $this->datagridValues['_sort_by'] = 'second_account';
        $this->datagridValues['_sort_order'] = 'DESC';
    }

    protected function configureRoutes(RouteCollection $collection) {
//        $collection->add('info_active');
//        $collection->remove('edit');
        $collection->remove('batch');
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('Account Info', array('class' => 'col-md-6'))
                ->add('code')
                ->add('first_account', 'sonata_type_model', array(), array('admin_code' => 'app.acme.account.admin.account'))
                ->add('first_cr')
                ->add('second_account', 'sonata_type_model', array(), array('admin_code' => 'app.acme.account.admin.account'))
                ->add('second_cr')
                ->add('ref_batch')
//                ->add('status', 'choice', array('choices' => Status::getActiveBooleanStatus()))
                ->end()

        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('code')
                ->add('first_account', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->add('first_cr')
                ->add('second_account', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->add('second_cr')
                ->add('ref_batch')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('code')
                ->add('first_account', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->add('first_cr', null, array('editable' => true))
                ->add('second_account', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->add('second_cr', null, array('editable' => true))
                ->add('ref_batch')
                ->add('_action', 'actions', array(
                    'actions' => array(
//                        'show' => array(),
//                        'edit' => array(),
//                        'delete' => array(),
                    )
                ))
        ;
    }

}
