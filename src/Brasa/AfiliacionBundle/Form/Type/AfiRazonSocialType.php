<?php

namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AfiRazonSocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit', textType::class, array('required' => true))
            ->add('dv', textType::class, array('required' => false))
            ->add('nombre', textType::class, array('required' => true))
            ->add('guardar', SubmitType::class);
    }

    public function getName()
    {
        return 'form';
    }
}

