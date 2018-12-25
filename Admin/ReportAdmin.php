<?php

namespace SDRO\AccountBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use SDRO\CoreBundle\Model\Type;
use SDRO\CoreBundle\Model\Status;

class ReportAdmin extends Admin {

    protected $parentAssociationMapping = 'report';
    protected $baseRoutePattern = 'report';

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('General', array('class' => 'col-md-6'))
                ->add('title')
                ->add('code')
//                ->end()
//                ->with('Account Title', array('class' => 'col-md-6'))
//                ->add('report_index')
//                ->add('report_index', 'sonata_type_model', array(
//                    'class' => 'SDRO\AccountBundle\Entity\ReportIndex',
//                    'query' => $this->modelManager->createQuery('SDRO\AccountBundle\Entity\ReportIndex'),
//                    'multiple' => true,
//                    'by_reference' => false,
//                    'btn_add' => false,
//                    'required' => false))

//                ->add('report_index', 'sonata_type_collection', array('by_reference' => null), array('edit' => 'inline','inline' => 'table','admin_code' => 'acme.account.admin.report_index'))
                ->end()

        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                ->add('title')
                ->add('code')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('title')
                ->add('code')
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
