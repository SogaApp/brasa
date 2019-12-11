<?php
namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InvTerceroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('nit', 'number', array('required' => true))
            ->add('digitoVerificacion', 'number', array('required' => true))    
            ->add('nombreCorto', 'text', array('required' => true))    
            ->add('nombres', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => false))    
            ->add('apellido2', 'text', array('required' => false))                
            ->add('direccion', 'text', array('required' => false))    
            ->add('telefono', 'text', array('required' => false))    
            ->add('celular', 'text', array('required' => false))    
            ->add('fax', 'text', array('required' => false))        
            ->add('email', 'text', array('required' => false))            
            ->add('guardar', 'submit')            
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}
