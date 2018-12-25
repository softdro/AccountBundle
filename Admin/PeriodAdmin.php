<?php

namespace App\Acme\AccountBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use App\Acme\CoreBundle\Model\Type;
use App\Acme\CoreBundle\Model\Status;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PeriodAdmin extends Admin {

    protected $parentAssociationMapping = 'Period';
    protected $baseRoutePattern = 'period';

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {

        $formMapper
                ->with('Account Info')
                ->add('title', null, array('attr' => array('placeholder' => 'Account Name')))
                ->add('start_date', DateType::class, array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
//                    'data' => new \DateTime('now'),
                ))
                ->add('end_date',DateType::class, array(
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
//                    'data' => new \DateTime('now'),
                ))
                ->add('enable', ChoiceType::class, array('choices' => Status::getActiveBooleanStatus()))
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
                ->add('enable')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('title')
                ->add('start_date')
                ->add('end_date')
                ->add('enable')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'show' => array(),
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
