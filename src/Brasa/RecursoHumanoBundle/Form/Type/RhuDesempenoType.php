<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuDesempenoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('dependenciaEvaluado', 'text', array('required' => true))
            ->add('dependenciaEvalua', 'text', array('required' => true))
            ->add('jefeEvalua', 'text', array('required' => true))
            ->add('cargoJefeEvalua', 'text', array('required' => true))
            ->add('fecha', 'date', array('required' => true))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

