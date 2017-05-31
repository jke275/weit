<?php

namespace WEIT\EspectacularesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EspectacularesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('calle')
            ->add('numero')
            ->add('largo')
            ->add('ancho')
            ->add('isActive')
            ->add('estado',  'choice', array('choices' => array('LIBRE' => 'Libre', 'BLOQUEADO' => 'Bloqueado', 'VENDIDO' => 'Vendido'), 'placeholder' => 'Selecciona el estado en que se encuentra'))
            ->add('save', 'submit', array('label' => 'Agregar'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WEIT\EspectacularesBundle\Entity\Espectaculares'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'weit_espectacularesbundle_espectaculares';
    }
}
