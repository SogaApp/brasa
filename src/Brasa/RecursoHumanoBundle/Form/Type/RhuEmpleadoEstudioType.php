<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmpleadoEstudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleadoEstudioTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'property' => 'nombre',
                'required' => true
            ))
            ->add('institucion', 'text', array('required' => false))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('gradoBachillerRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuGradoBachiller',
                'query_builder' => function (EntityRepository $er)  {
                return $er->createQueryBuilder('c');},
                'property' => 'grado',
                'required' => false))
            ->add('fechaInicio','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false,'attr' => array('class' => 'date',)))
            ->add('fechaTerminacion','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false,'attr' => array('class' => 'date',)))
            ->add('validarVencimiento', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('fechaVencimientoCurso','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false,'attr' => array('class' => 'date',)))
            ->add('graduado', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                
            ->add('titulo', 'text', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('numeroRegistro', 'text', array('required' => false))                    
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

