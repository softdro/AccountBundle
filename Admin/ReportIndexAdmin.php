<?php

namespace App\Acme\AccountBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use App\Acme\CoreBundle\Model\Type;
use App\Acme\CoreBundle\Model\Status;

class ReportIndexAdmin extends Admin {

    protected $parentAssociationMapping = 'report_index';
    protected $baseRoutePattern = 'report_index';

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('General', array('class' => 'col-md-6'))
                ->add('report', 'entity',array(), array(
                    'admin_code' => 'app.acme.account.admin.report',
                ))
//                ->add('account')
//                ->add('account', 'sonata_type_model_list', array(  ), array(  'placeholder' => 'No author selected' ))
                ->add('account',array(), array(
                    'admin_code' => 'app.acme.account.admin.account',
                ))
                ->add('account_head')
//                ->add('account_group')
                ->add('group_title')
                ->add('priority')
                ->end()

        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('report', null, array('show_filter' => true,
                    'admin_code' => 'app.acme.account.admin.report'))
                ->add('account', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->add('account_head', null, array('show_filter' => true))
//                ->add('account_group', null, array('show_filter' => true, 'admin_code' => 'acme.account.admin.account_group'))
                ->add('group_title')
                ->add('priority')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('report', null, array('admin_code' => 'app.acme.account.admin.report'))
                ->add('account', null, array('admin_code' => 'app.acme.account.admin.account'))
                ->add('account_head')
//                ->add('account_group')
                ->add('group_title')
                ->add('priority')
                ->add('_action', 'actions', array(
                    'actions' => array(
//                        'show' => array(),
                        'edit' => array(),
//                        'delete' => array(),
                    )
                ))
        ;
    }

//    /**
//     * @return array
//     */
//    public function getBatchActions() {
//        $actions = parent::getBatchActions();
//
//        $actions['enabled'] = array(
//            'label' => $this->trans('batch_enable_comments'),
//            'ask_confirmation' => true,
//        );
//
//        $actions['disabled'] = array(
//            'label' => $this->trans('batch_disable_comments'),
//            'ask_confirmation' => false
//        );
//
//        return $actions;
//    }
}
