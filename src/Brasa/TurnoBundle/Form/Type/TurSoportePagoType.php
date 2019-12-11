<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurSoportePagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                       
            ->add('dias', NumberType::class)
            ->add('diasTransporte', NumberType::class)                
            ->add('horas', NumberType::class)
            ->add('horasDiurnas', NumberType::class)
            ->add('horasNocturnas', NumberType::class)  
            ->add('horasDescanso', NumberType::class)  
            ->add('horasFestivasDiurnas', NumberType::class)  
            ->add('horasFestivasNocturnas', NumberType::class)  
            ->add('horasExtrasOrdinariasDiurnas', NumberType::class)
            ->add('horasExtrasOrdinariasNocturnas', NumberType::class)
            ->add('horasExtrasFestivasDiurnas', NumberType::class)
            ->add('horasExtrasFestivasNocturnas', NumberType::class)
            
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

