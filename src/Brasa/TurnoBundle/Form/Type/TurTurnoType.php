<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurTurnoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTurnoPk', TextType::class, array('required' => true))
            ->add('nombre', TextType::class, array('required' => true))            
            ->add('horaDesde', TimeType::class, array('required' => true))
            ->add('horaHasta', TimeType::class, array('required' => true))
            ->add('horas', NumberType::class, array('required' => true))                
            ->add('horasNomina', NumberType::class, array('required' => true))                
            ->add('horasDiurnas', NumberType::class, array('required' => true))                
            ->add('horasNocturnas', NumberType::class, array('required' => true)) 
            ->add('complementario', CheckboxType::class, array('required'  => false))
            ->add('novedad', CheckboxType::class, array('required'  => false))
            ->add('descanso', CheckboxType::class, array('required'  => false))
            ->add('incapacidad', CheckboxType::class, array('required'  => false))
            ->add('licencia', CheckboxType::class, array('required'  => false))
            ->add('licenciaNoRemunerada', CheckboxType::class, array('required'  => false))
            ->add('vacacion', CheckboxType::class, array('required'  => false))
            ->add('ingreso', CheckboxType::class, array('required'  => false))
            ->add('retiro', CheckboxType::class, array('required'  => false))
            ->add('induccion', CheckboxType::class, array('required'  => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

