<?php

namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AfiClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('asesorRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.estado = 1')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('formaPagoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenFormaPago',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('fp')
                        ->orderBy('fp.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('razonSocialRel', EntityType::class, array(
                'class' => 'Brasa\AfiliacionBundle\Entity\AfiRazonSocial',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rs')
                        ->orderBy('rs.nombre');
                },
                'placeholder' => 'Seleccione un registro',
                'choice_label' => 'nombre',
                'required' => false
            ))
            ->add('entidadRiesgoRel', EntityType::class, array(
                'class' => 'Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },
                'placeholder' => 'Seleccione un registro',
                'choice_label' => 'nombre',
                'required' => false
            ))
            ->add('nit', textType::class, array('required' => true))
            ->add('digitoVerificacion', textType::class, array('required' => false))
            ->add('nombreCorto', textType::class, array('required' => true))
            ->add('plazoPago', NumberType::class, array('required' => false))
            ->add('direccion', textType::class, array('required' => false))
            ->add('barrio', textType::class, array('required' => false))
            ->add('telefono', textType::class, array('required' => false))
            ->add('celular', textType::class, array('required' => false))
            ->add('fax', textType::class, array('required' => false))
            ->add('email', textType::class, array('required' => false))
            ->add('contacto', textType::class, array('required' => false))
            ->add('celularContacto', textType::class, array('required' => false))
            ->add('telefonoContacto', textType::class, array('required' => false))
            ->add('afiliacion', NumberType::class, array('required' => false))
            ->add('administracion', NumberType::class, array('required' => false))
            ->add('generaPension', CheckboxType::class, array('required' => false))
            ->add('generaSalud', CheckboxType::class, array('required' => false))
            ->add('generaRiesgos', CheckboxType::class, array('required' => false))
            ->add('generaCaja', CheckboxType::class, array('required' => false))
            ->add('generaSena', CheckboxType::class, array('required' => false))
            ->add('generaIcbf', CheckboxType::class, array('required' => false))
            ->add('porcentajePension', NumberType::class, array('required' => true))
            ->add('porcentajeSalud', NumberType::class, array('required' => true))
            ->add('porcentajeCaja', NumberType::class, array('required' => true))
            ->add('redondearCobro', CheckboxType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('codigoSucursal', textType::class, array('required' => false))
            ->add('independiente', CheckboxType::class, array('required' => false))
            ->add('tipoIdentificacion', ChoiceType::class, array('choices' => array('NI' => 'NIT', 'CC' => 'CEDULA DE CIUDADANIA')))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label' => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

