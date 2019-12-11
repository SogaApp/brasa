<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AfiPagoCursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add('cuentaRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCuenta',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.codigoCuentaPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('proveedorRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiProveedor',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.codigoProveedorPk', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))                
            ->add('soporte', TextType::class, array('required' => false))
            ->add('comentarios', TextType::class, array('required' => false))                            
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

