<?php
namespace Brasa\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GenAsesorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('nombre', 'text', array('required' => true))
            ->add('direccion', 'text', array('required' => false))    
            ->add('telefono', 'text', array('required' => false))    
            ->add('celular', 'text', array('required' => false))
            ->add('email', 'text', array('required' => false))
            ->add('estado', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))    
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
