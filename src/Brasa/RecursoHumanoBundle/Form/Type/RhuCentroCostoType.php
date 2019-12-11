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

class RhuCentroCostoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('clienteRel', EntityType::class,
                array('class' => 'BrasaRecursoHumanoBundle:RhuCliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => false
                ))                
            ->add('periodoPagoRel', EntityType::class,
                array('class' => 'BrasaRecursoHumanoBundle:RhuPeriodoPago',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true,                
                ))
            ->add('sucursalRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSucursal',
                'choice_label' => 'nombre',
                'required' => true))
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('nombre', TextType::class, array('required' => true))
            ->add('fechaUltimoPagoProgramado', DateType::class)
            ->add('fechaUltimoPagoPrima', DateType::class)
            ->add('fechaUltimoPagoCesantias', DateType::class)
            ->add('horaPagoAutomatico', TimeType::class)
            ->add('estadoActivo', CheckboxType::class, array('required'  => false))
            ->add('administrativo', CheckboxType::class, array('required'  => false))
            ->add('generarPagoAutomatico', ChoiceType::class, array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('porcentajeAdministracion', NumberType::class, array('required' => false))
            ->add('valorAdministracion', NumberType::class, array('required' => false))
            ->add('diasPago', TextType::class, array('required' => true))            
            ->add('generaServicioCobrar', ChoiceType::class, array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('descansoOrdinario', CheckboxType::class, array('required'  => false))
            ->add('pagarDia31', CheckboxType::class, array('required'  => false))
            ->add('secuencia', NumberType::class, array('required' => false))                            
            ->add('codigoTurnoFijoNominaFk', TextType::class, array('required' => false))                            
            ->add('codigoTurnoFijoDescansoFk', TextType::class, array('required' => false))                            
            ->add('diasDescansoFijo', NumberType::class, array('required' => false))  
            ->add('descansoCompensacionDominicales', CheckboxType::class, array('required'  => false))                            
            ->add('descansoCompensacionFijo', CheckboxType::class, array('required'  => false))
            ->add('diasDescansoCompensacionFijo', NumberType::class, array('required' => false))                              
            ->add('generaSoportePago', CheckboxType::class, array('required'  => false))                            
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

