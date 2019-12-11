<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuExamenType extends AbstractType
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
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'property' => 'nombre',
            ))           
            ->add('fecha','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('identificacion', 'number', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => true))
            ->add('fechaNacimiento','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('controlPago', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

