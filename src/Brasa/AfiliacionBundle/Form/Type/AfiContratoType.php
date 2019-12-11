<?php

namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AfiContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clienteRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiCliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'required' => true))
            ->add('sucursalRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiSucursal',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('cargoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('entidadSaludRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('es')
                        ->orderBy('es.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('entidadPensionRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'choice_label' => 'nombre',
            ))
            ->add('entidadCajaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('ssoTipoCotizanteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoTipoCotizante',
                'choice_label' => 'nombre',
            ))
            ->add('ssoSubtipoCotizanteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSubtipoCotizante',
                'choice_label' => 'nombre',
            ))
            ->add('clasificacionRiesgoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuClasificacionRiesgo',
                'choice_label' => 'nombre',
            ))
            ->add('numero', TextType::class, array('required' => false))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'years' => range(1990, intval(date('Y')) + 10)))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'years' => range(1990, intval(date('Y')) + 10)))
            ->add('indefinido', CheckboxType::class, array('required' => false))
            ->add('vrSalario', NumberType::class, array('required' => true))
            ->add('generaPension', CheckboxType::class, array('required' => false))
            ->add('generaSalud', CheckboxType::class, array('required' => false))
            ->add('generaRiesgos', CheckboxType::class, array('required' => false))
            ->add('generaCaja', CheckboxType::class, array('required' => false))
            ->add('generaSena', CheckboxType::class, array('required' => false))
            ->add('generaIcbf', CheckboxType::class, array('required' => false))
            ->add('porcentajePension', NumberType::class, array('required' => true))
            ->add('porcentajeSalud', NumberType::class, array('required' => true))
            ->add('porcentajeCaja', NumberType::class, array('required' => true))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('numeroRecibo', TextType::class, array('required' => false))
            ->add('valor', NumberType::class, array('required' => true))
            ->add('formaPago', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function getName()
    {
        return 'form';
    }
}

