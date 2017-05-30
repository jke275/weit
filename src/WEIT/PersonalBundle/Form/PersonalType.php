<?php

namespace WEIT\PersonalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('nombre')
            ->add('apellidos')
            ->add('role',  'choice', array('choices' => array('ROLE_ADMIN' => 'Administrador', 'ROLE_COMERCIAL' => 'Comercial'), 'placeholder' => 'Selecciona un tipo de usuario'))
            ->add('password', 'password')
            ->add('isActive')
            ->add('crear')
            ->add('editar')
            ->add('borrar')
             ->add('save', 'submit', array('label' => 'Agregar'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WEIT\PersonalBundle\Entity\Personal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'weit_personalbundle_personal';
    }
}
