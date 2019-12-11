<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuPermisoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('permisoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPermisoTipo',
                'property' => 'nombre',
                'required' => true))
            ->add('fechaPermiso', 'date', array('format' => 'yyyyMMdd'))    
            ->add('horaSalida', 'time', array('required' => true))
            ->add('horaLlegada', 'time', array('required' => true))                
            ->add('motivo', 'textarea', array('required' => true))
            ->add('jefeAutoriza', 'text', array('required' => true))
            ->add('observaciones', 'textarea', array('required' => false))
            ->add('afectaHorario', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

