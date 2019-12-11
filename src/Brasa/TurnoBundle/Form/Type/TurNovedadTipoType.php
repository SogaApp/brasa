<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurNovedadTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder       
            ->add('turnoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurTurno',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('nombre', TextType::class, array('required' => true))  
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

