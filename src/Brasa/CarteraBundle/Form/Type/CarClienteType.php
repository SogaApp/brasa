<?php
namespace Brasa\CarteraBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CarClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))  
            ->add('asesorRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenAsesor',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('a')
                    ->where('a.estado = 1')
                    ->orderBy('a.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('formaPagoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenFormaPago',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('fp')
                    ->orderBy('fp.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                              
            ->add('nit', 'number', array('required' => true))
            ->add('digitoVerificacion', 'text', array('required' => false))  
            ->add('nombreCorto', 'text', array('required' => true))  
            ->add('plazoPago', 'number', array('required' => false)) 
            ->add('direccion', 'text', array('required' => false))  
            ->add('telefono', 'text', array('required' => false))                              
            ->add('celular', 'text', array('required' => false))                              
            ->add('fax', 'text', array('required' => false))                              
            ->add('email', 'text', array('required' => false))                                                                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

