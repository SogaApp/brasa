<?php
namespace Brasa\CarteraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CarNotaCreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('fechaPago', 'date', array('format' => 'yyyyMMdd'))
            ->add('cuentaRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCuenta',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true)) 
            ->add('notaCreditoConceptoRel', 'entity', array(
                'class' => 'BrasaCarteraBundle:CarNotaCreditoConcepto',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ndc')
                    ->orderBy('ndc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                                  
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

