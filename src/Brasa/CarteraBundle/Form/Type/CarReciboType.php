<?php
namespace Brasa\CarteraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CarReciboType extends AbstractType
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
            ->add('reciboTipoRel', 'entity', array(
                'class' => 'BrasaCarteraBundle:CarReciboTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');},
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

