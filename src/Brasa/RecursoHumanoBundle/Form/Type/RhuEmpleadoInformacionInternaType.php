<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmpleadoInformacionInternaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacion', 'text', array('required' => true))
            ->add('informacionInternaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo',
                'property' => 'nombre',
            ))
            ->add('fecha', 'date', array('data' => new \DateTime('now'), 'required' => true))    
            ->add('comentarios', 'textarea', array('required' => false))    
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

