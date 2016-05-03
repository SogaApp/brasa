<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AfiCursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('clienteRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiCliente',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))                                             
            ->add('empleadoRel', 'entity', array(
                'class' => 'BrasaAfiliacionBundle:AfiEmpleado',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => false))
            ->add('fechaVence', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaProgramacion', 'date', array('format' => 'yyyyMMdd'))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

