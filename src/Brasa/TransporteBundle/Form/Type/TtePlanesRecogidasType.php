<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TtePlanRecogidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                 
            ->add('conductorRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteConductor',
                'property' => 'nombreCorto',
            ))
            ->add('vehiculoRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteVehiculo',
                'property' => 'placa',
            ))                                 
            ->add('vrFletePagado', 'text')            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

