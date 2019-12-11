<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuTurnoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTurnoPk', 'text', array('required' => true))
            ->add('nombre', 'text', array('required' => true))
            ->add('horaDesde', 'time', array('required' => true))
            ->add('horaHasta', 'time', array('required' => true))
            ->add('horas', 'text', array('required' => true))    
            ->add('horasDiurnas', 'number', array('required' => true))        
            ->add('horasNocturnas', 'number', array('required' => true))        
            ->add('horasPausa', 'number', array('required' => true))        
            ->add('descanso', 'checkbox', array('required'  => false))                
            ->add('novedad', 'checkbox', array('required'  => false))                
            ->add('incapacidad', 'checkbox', array('required'  => false))                
            ->add('licencia', 'checkbox', array('required'  => false))                
            ->add('vacacion', 'checkbox', array('required'  => false))                
            ->add('salidaDiaSiguiente', 'checkbox', array('required'  => false))
            ->add('comentarios', 'textarea', array('required' => false))        
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
