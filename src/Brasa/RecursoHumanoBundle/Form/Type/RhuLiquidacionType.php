<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuLiquidacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vrCesantias', 'number', array('required' => true))
            ->add('vrInteresesCesantias', 'number', array('required' => true))
            ->add('vrPrima', 'number', array('required' => true))
            ->add('vrSalarioVacaciones', 'number', array('required' => true))
            ->add('vrVacaciones', 'number', array('required' => true))
            ->add('diasCesantias', 'number', array('required' => true))
            ->add('diasVacaciones', 'number', array('required' => true))
            ->add('diasPrimas', 'number', array('required' => true))                
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

