<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class AfiFacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('facturaTipoRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiFacturaTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ft')
                    ->orderBy('ft.codigoFacturaTipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('cantidad', integerType::class,array('required' => false))
            ->add('valorPorEmpleado', integerType::class,array('required' => false))
            ->add('soporte', textType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('afiliacion', CheckboxType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

