<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuDotacionElementoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dotacion', 'text', array('required' => true))
            ->add('dotacionElementoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDotacionElementoTipo',
                'property' => 'nombre',
            ))    
            ->add('guardar', 'submit', array('label' => 'Guardar'))
            ->add('guardarynuevo', 'submit', array('label' => 'Guardar y nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}
