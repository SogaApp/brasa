<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuSeleccionEntrevistaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                
            ->add('seleccionEntrevistaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionEntrevistaTipo',
                'property' => 'nombre',
                'required' => true,
            ))    
            ->add('resultado', 'text', array('required' => false))
            ->add('resultadoCuantitativo', 'number', array('required' => false))
            ->add('fecha', 'datetime', array('required' => true, 'data' => new \DateTime('now')))
            ->add('nombreQuienEntrevista', 'text', array('required' => false))    
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

