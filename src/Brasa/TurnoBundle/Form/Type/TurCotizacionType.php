<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurCotizacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('prospectoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurProspecto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => false))                    
            ->add('sectorRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurSector',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))    
            ->add('fechaVence', DateType::class, array('format' => 'yyyyMMdd'))                            
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

