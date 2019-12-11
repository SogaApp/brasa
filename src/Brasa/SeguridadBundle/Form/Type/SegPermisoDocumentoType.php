<?php
namespace Brasa\SeguridadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SegPermisoDocumentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingreso', 'checkbox', array('required'  => false))            
            ->add('nuevo', 'checkbox', array('required'  => false))            
            ->add('editar', 'checkbox', array('required'  => false))                            
            ->add('eliminar', 'checkbox', array('required'  => false))                                            
            ->add('autorizar', 'checkbox', array('required'  => false))                                            
            ->add('desautorizar', 'checkbox', array('required'  => false))                                            
            ->add('aprobar', 'checkbox', array('required'  => false))
            ->add('anular', 'checkbox', array('required'  => false))
            ->add('imprimir', 'checkbox', array('required'  => false))    
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
