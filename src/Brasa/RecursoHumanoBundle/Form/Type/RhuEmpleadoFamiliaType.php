<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmpleadoFamiliaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empleadoFamiliaParentescoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoFamiliaParentesco',
                'property' => 'nombre',
            ))               
            ->add('nombres', 'text', array('required' => true))
            ->add('entidadSaludRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'property' => 'nombre',
                'required' => false,
            ))    
            ->add('entidadCajaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'property' => 'nombre',
                'required' => false,
            ))   
            ->add('fechaNacimiento','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'required' => false, 'attr' => array('class' => 'date',)))                
            ->add('ocupacion', 'text', array('required' => true))
            ->add('telefono', 'text', array('required' => false))
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))
            ->add('guardar', 'submit')  
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

