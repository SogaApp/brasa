<?php

namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AfiConfiguracionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoConfiguracionPk', TextType::class)
            ->add('informacionLegalFactura', TextType::class)
            ->add('informacionPagoFactura', TextType::class)
            ->add('informacionContactoFactura', TextType::class)
            ->add('informacionResolucionDianFactura', TextType::class)
            ->add('informacionResolucionSupervigilanciaFactura', TextType::class)
            ->add('porcentajeInteres')
            ->add('diasInteres')
            ->add('guardar', SubmitType::class);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Brasa\AfiliacionBundle\Entity\AfiConfiguracion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'brasa_afiliacionbundle_aficonfiguracion';
    }
}
