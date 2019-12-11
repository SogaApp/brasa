<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('contratoTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoTipo',
                'choice_label' => 'nombre',
            ))                 
            ->add('contratoGrupoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuContratoGrupo',
                'choice_label' => 'nombre',
            ))                 
            ->add('clasificacionRiesgoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuClasificacionRiesgo',
                'choice_label' => 'nombre',
            ))                 
            ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'choice_label' => 'nombre',
            ))  
            ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('tipoTiempoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoTiempo',
                'choice_label' => 'nombre',
            )) 
            ->add('tipoPensionRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoPension',
                'choice_label' => 'nombre',
            ))                            
            ->add('tipoSaludRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuTipoSalud',
                'choice_label' => 'nombre',
            ))                            
            ->add('cargoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
            ))  
            ->add('ssoTipoCotizanteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoTipoCotizante',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('tc')
                    ->orderBy('tc.codigoTipoCotizantePk', 'ASC');},
                'choice_label' => 'nombre',
            ))                            
            ->add('ssoSubtipoCotizanteRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSubtipoCotizante',
                'choice_label' => 'nombre',
            ))
            ->add('salarioTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSalarioTipo',
                'choice_label' => 'nombre',
                'required' => true
            ))                
            ->add('entidadSaludRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadSalud',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('es')
                    ->orderBy('es.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('entidadCesantiaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCesantia',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ec')
                    ->orderBy('ec.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                
            ->add('entidadPensionRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadPension',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'choice_label' => 'nombre',
            ))
            ->add('entidadCajaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEntidadCaja',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ec')
                    ->orderBy('ec.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                
            //->add('fechaDesde', 'date', array('required' => true))
            ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                
            //->add('fechaHasta', 'date', array('required' => true))                
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                
            ->add('horarioTrabajo', TextType::class, array('required' => false)) 
            ->add('vrSalario', NumberType::class, array('required' => true))  
            //->add('numero', 'text', array('required' => true))                                           
            ->add('cargoDescripcion', TextType::class, array('required' => false))                                                                       
            ->add('comentarios', TextareaType::class, array('required' => false))            
            ->add('salarioIntegral', CheckboxType::class, array('required'  => false))
            ->add('limitarHoraExtra', CheckboxType::class, array('required'  => false))     
            ->add('vrDevengadoPactado', NumberType::class, array('required' => true))
            ->add('ciudadContratoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('ciudadLaboraRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))   
            ->add('turnoFijoOrdinario', CheckboxType::class, array('required'  => false))   
            ->add('secuencia', NumberType::class, array('required' => false))                             
            ->add('guardar', SubmitType::class);        
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}

