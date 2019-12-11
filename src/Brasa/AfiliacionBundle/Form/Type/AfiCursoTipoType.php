<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AfiCursoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('proveedorRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiProveedor',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.codigoProveedorPk', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))                 
            ->add('nombre', textType::class, array('required' => true))                          
            ->add('precio', NumberType::class, array('required' => true))              
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

