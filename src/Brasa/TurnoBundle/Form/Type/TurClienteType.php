<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder   
            ->add('tipoIdentificacionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ti')
                    ->orderBy('ti.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                
            ->add('sectorRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurSector',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true)) 
            ->add('formaPagoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenFormaPago',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('fp')
                    ->orderBy('fp.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                              
            ->add('asesorRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenAsesor',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('a')
                    ->orderBy('a.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                            
            ->add('sectorComercialRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenSectorComercial',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('sc')
                    ->orderBy('sc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))   
            ->add('coberturaRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCobertura',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))    
            ->add('dimensionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenDimension',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('d')
                    ->orderBy('d.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('origenCapitalRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenOrigenCapital',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('oc')
                    ->orderBy('oc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true)) 
            ->add('origenJudicialRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenOrigenJudicial',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('oj')
                    ->orderBy('oj.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))  
            ->add('sectorEconomicoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenSectorEconomico',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('se')
                    ->orderBy('se.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                            
            ->add('nit', NumberType::class, array('required' => true))
            ->add('digitoVerificacion', TextType::class, array('required' => false))  
            ->add('nombreCorto', TextType::class, array('required' => true)) 
            ->add('nombreCompleto', TextType::class, array('required' => true))
            ->add('nombre1', TextType::class, array('required' => false))
            ->add('nombre2', TextType::class, array('required' => false))
            ->add('apellido1', TextType::class, array('required' => false))
            ->add('apellido2', TextType::class, array('required' => false))                            
            ->add('estrato', TextType::class, array('required' => false))  
            ->add('plazoPago', NumberType::class, array('required' => false)) 
            ->add('direccion', TextType::class, array('required' => false))  
            ->add('barrio', TextType::class, array('required' => false))  
            ->add('telefono', TextType::class, array('required' => false))                              
            ->add('celular', TextType::class, array('required' => false))                              
            ->add('fax', TextType::class, array('required' => false))                              
            ->add('email', TextType::class, array('required' => false))                              
            ->add('gerente', TextType::class, array('required' => false))                  
            ->add('celularGerente', TextType::class, array('required' => false))                  
            ->add('financiero', TextType::class, array('required' => false))                  
            ->add('celularFinanciero', TextType::class, array('required' => false))                                  
            ->add('contacto', TextType::class, array('required' => false))                  
            ->add('celularContacto', TextType::class, array('required' => false))  
            ->add('telefonoContacto', TextType::class, array('required' => false))
            ->add('facturaAgrupada', CheckboxType::class, array('required'  => false))                            
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

