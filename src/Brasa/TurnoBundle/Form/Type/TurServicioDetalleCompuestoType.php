<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurServicioDetalleCompuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder              
            ->add('conceptoServicioRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurConceptoServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cs')
                    ->orderBy('cs.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))               
            ->add('modalidadServicioRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurModalidadServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ms')
                    ->orderBy('ms.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('cantidad', NumberType::class)
            ->add('lunes', CheckboxType::class, array('required'  => false))
            ->add('martes', CheckboxType::class, array('required'  => false))
            ->add('miercoles', CheckboxType::class, array('required'  => false))
            ->add('jueves', CheckboxType::class, array('required'  => false))
            ->add('viernes', CheckboxType::class, array('required'  => false))
            ->add('sabado', CheckboxType::class,  array('required'  => false))
            ->add('domingo', CheckboxType::class, array('required'  => false))
            ->add('festivo', CheckboxType::class, array('required'  => false))                                              
            ->add('dia31', CheckboxType::class, array('required'  => false))                            
            ->add('liquidarDiasReales', CheckboxType::class, array('required'  => false))                                                       
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

