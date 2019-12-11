<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurConceptoServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))            
            ->add('nombreFacturacion', TextType::class, array('required' => true))            
            ->add('nombreFacturacionAdicional', TextType::class, array('required' => true))            
            ->add('tipo', NumberType::class)
            ->add('porBaseIva', NumberType::class)
            ->add('porIva', NumberType::class)
            ->add('horas', NumberType::class, array('required' => true))                
            ->add('horasDiurnas', NumberType::class, array('required' => true))                
            ->add('horasNocturnas', NumberType::class, array('required' => true))
            ->add('vrCosto', NumberType::class)                 
            //->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

