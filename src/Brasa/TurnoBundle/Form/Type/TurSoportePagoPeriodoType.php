<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurSoportePagoPeriodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder       
            ->add('centroCostoRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cc')
                    ->where('cc.generaSoportePago = 1')
                    ->orderBy('cc.codigoCentroCostoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                                                                                                                   
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd'))  
            ->add('diasPeriodo', NumberType::class, array('required' => false))
            ->add('festivos', NumberType::class, array('required' => false))
            ->add('diasAdicionales', NumberType::class, array('required' => false))
            ->add('descansoFestivoFijo', CheckboxType::class, array('required'  => false))                            
            ->add('pagarDia31', CheckboxType::class, array('required'  => false))                            
            ->add('diaDescansoCompensacion', NumberType::class, array('required' => false))                            
            ->add('guardar', SubmitType::class);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

