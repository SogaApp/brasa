<?php
namespace Brasa\TransporteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('listaPrecioRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteListaPrecio',
                'property' => 'nombre',
            ))
            ->add('nit', 'number', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('liquidarAutomaticamenteFlete','choice',array('choices' => array('0' => 'NO', '1' => 'SI')))    
            ->add('liquidarAutomaticamenteManejo','choice',array('choices' => array('0' => 'NO', '1' => 'SI')))        
            ->add('porcentajeManejo', 'text', array('required' => false))
            ->add('vrManejoMinimoUnidad', 'text', array('required' => false))    
            ->add('vrManejoMinimoDespacho', 'text', array('required' => false))        
            ->add('descuentoKilos', 'text', array('required' => false))            
            ->add('ctPesoMinimoUnidad', 'text', array('required' => false))            
            ->add('PagaManejoCorriente','choice',array('choices' => array('0' => 'NO', '1' => 'SI')))        
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}

