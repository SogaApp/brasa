<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AfiCursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('entidadEntrenamientoRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiEntidadEntrenamiento',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))                               
            ->add('fechaVence', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaProgramacion', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

