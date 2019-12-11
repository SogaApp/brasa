<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuSoportePagoHorarioDetalleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                
            ->add('dias', 'number', array('required' => true))
            ->add('horas', 'number', array('required' => true))
            ->add('horasDescanso', 'number', array('required' => true))
            ->add('horasDiurnas', 'number', array('required' => true))
            ->add('horasNocturnas', 'number', array('required' => true))
            ->add('horasFestivasDiurnas', 'number', array('required' => true))
            ->add('horasFestivasNocturnas', 'number', array('required' => true))
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
