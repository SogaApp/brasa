<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuDisciplinarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('disciplinarioTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDisciplinarioTipo',
                'property' => 'nombre',
            ))               
            ->add('disciplinarioMotivoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDisciplinarioMotivo',
                'property' => 'nombre',
                'required' => false,
            ))                 
            ->add('asunto', 'textarea', array('required' => false))            
            ->add('diasSuspencion', 'text', array('required' => false)) 
            ->add('reentrenamiento', 'choice', array('choices'   => array('0' => 'NO', '1' => 'SI'))) 
            ->add('puesto', 'text', array('required' => false))
            ->add('fechaNotificacion', 'date', array('format' => 'yyyyMMdd')) 
            ->add('fechaIncidente', 'date', array('format' => 'yyyyMMdd'))    
            ->add('fechaDesdeSancion', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaHastaSancion', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaIngresoTrabajo', 'date', array('format' => 'yyyyMMdd'))    
            ->add('estadoSuspension', 'checkbox', array('required'  => false))
            ->add('estadoProcede', 'checkbox', array('required'  => false))
            ->add('comentarios', 'textarea', array('required' => false))            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

