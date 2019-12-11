<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuAcreditacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('acreditacionTipoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAcreditacionTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('at')
                    ->orderBy('at.codigoAcreditacionTipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('acreditacionRechazoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAcreditacionRechazo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ar')
                    ->orderBy('ar.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false))                            
            ->add('academiaRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAcademia',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                                  
            ->add('estadoRechazado', CheckboxType::class, array('required'  => false))                            
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('numeroRegistro', TextType::class, array('required' => false))                            
            ->add('fechaVenceCurso', DateType::class, array('format' => 'yyyyMMdd'))                             
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

