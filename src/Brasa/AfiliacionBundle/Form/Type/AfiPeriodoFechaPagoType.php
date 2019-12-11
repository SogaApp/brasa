<?php

namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AfiPeriodoFechaPagoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('diaHabil')
            ->add('anio')
            ->add('dosUltimosDigitosInicioNit')
            ->add('dosUltimosDigitosFinNit')
            ->add('guardar', SubmitType::class)   
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\AfiliacionBundle\Entity\AfiPeriodoFechaPago'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brasa_afiliacionbundle_afiperiodofechapago';
    }
}
