<?php

namespace WEIT\EspectacularesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class VentasType extends AbstractType
{
    private $espectacular;
    private $cliente;

    public function __contruct($espec, $cliente)
    {
        $this->espectacular = $espec;
        $this->cliente = cliente;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $espectacular = $this->espectacular;
        $cliente = $this->cliente;
        $builder
            ->add('fechaInicio')
            ->add('fechaFin')
            ->add('save', 'submit', array('label' => 'Agregar'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WEIT\EspectacularesBundle\Entity\Ventas'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'weit_espectacularesbundle_ventas';
    }
}
