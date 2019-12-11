<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuSeleccionPruebaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seleccionPruebaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionPruebaTipo',
                'property' => 'nombre',
            ))    
            ->add('resultado', 'text', array('required' => false))
            ->add('resultadoCuantitativo', 'number', array('required' => false))
            ->add('nombreQuienHacePrueba', 'text', array('required' => false))    
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

