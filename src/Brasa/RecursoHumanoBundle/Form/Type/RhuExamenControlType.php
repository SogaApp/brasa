<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuExamenControlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('examenClaseRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenClase',
                'property' => 'nombre',
            ))
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'property' => 'nombre',
            ))    
            ->add('entidadExamenRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadExamen',
                'property' => 'nombre',
            ))
            ->add('fecha','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))           
            ->add('comentarios', 'textarea', array('required' => false))            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

