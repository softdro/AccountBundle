<?php

namespace SDRO\AccountBundle\Form;

use SDRO\AccountBundle\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use SDRO\AccountBundle\Form\TagType;

class TaskType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('description')
//                ->add('tags', CollectionType::class, array(
//                    'entry_type' => TagType::class,
//                    'entry_options' => array('label' => false),
//                    'allow_add' => true,
//                ))
                ->add('tags', 'collection', array(
                    'entry_type' => new TagType(),
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SDRO\AccountBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'acme_accountbundle_task';
    }

}
