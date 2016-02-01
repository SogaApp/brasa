<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('sectorRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurSector',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.nombre', 'ASC');},
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
            ->add('estrato', 'text', array('required' => false))  
            ->add('plazoPago', 'number', array('required' => false)) 
            ->add('direccion', 'text', array('required' => false))  
            ->add('telefono', 'text', array('required' => false))                              
            ->add('celular', 'text', array('required' => false))                              
            ->add('fax', 'text', array('required' => false))                              
            ->add('email', 'text', array('required' => false))                              
            ->add('gerente', 'text', array('required' => false))                  
            ->add('celularGerente', 'text', array('required' => false))                  
            ->add('financiero', 'text', array('required' => false))                  
            ->add('celularFinanciero', 'text', array('required' => false))                                  
            ->add('contacto', 'text', array('required' => false))                  
            ->add('celularContacto', 'text', array('required' => false))  
            ->add('telefonoContacto', 'text', array('required' => false))  
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

