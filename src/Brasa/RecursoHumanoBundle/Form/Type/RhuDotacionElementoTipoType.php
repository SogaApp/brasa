<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuDotacionElementoTipoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required' => true))
            ->add('guardar', 'submit', array('label' => 'Guardar'))
            ->add('guardarynuevo', 'submit', array('label' => 'Guardar y nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}
