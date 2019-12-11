<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurNotaCreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder  
            ->add('facturaTipoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurFacturaTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ft')
                    ->where('ft.tipo = :tipo')
                    ->setParameter('tipo', 2)
                    ->orderBy('ft.codigoFacturaTipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))  
            ->add('facturaSubtipoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurFacturaSubtipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('fs')
                    ->orderBy('fs.codigoFacturaSubtipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('facturaServicioRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurFacturaServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ft')
                    ->orderBy('ft.codigoFacturaServicioPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('imprimirRelacion', CheckboxType::class, array('required'  => false))  
            ->add('imprimirAgrupada', CheckboxType::class, array('required'  => false))
            ->add('soporte', TextType::class, array('required' => false))
            ->add('descripcion', TextType::class, array('required' => false))                            
            ->add('tituloRelacion', TextType::class, array('required' => false))                            
            ->add('detalleRelacion', TextType::class, array('required' => false))                            
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

