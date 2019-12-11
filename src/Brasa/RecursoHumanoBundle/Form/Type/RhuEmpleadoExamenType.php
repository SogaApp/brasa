<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmpleadoExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('examenTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenTipo',
                'property' => 'nombre',
            ))               
            ->add('fechaVencimiento', 'date')      
            ->add('validarVencimiento', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                                        
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

