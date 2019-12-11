<?php
namespace Brasa\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GenTercerosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clasificacionTributariaRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenClasificacionTributaria',
                        'property' => 'nombreClasificacionTributaria',))
            ->add('nit', 'number', array('required' => true))
            ->add('digitoVerificacion', 'number', array('required' => true))    
            ->add('nombreCorto', 'text', array('required' => true))    
            ->add('nombres', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => false))    
            ->add('apellido2', 'text', array('required' => false))
            ->add('formaPagoClienteRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenFormaPago',
                        'property' => 'nombre',))
            ->add('formaPagoProveedorRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenFormaPago',
                        'property' => 'nombre',))
            ->add('asesorRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenAsesor',
                        'property' => 'nombre',))
            ->add('plazoPagoCliente', 'number', array('required' => true))    
            ->add('plazoPagoProveedor', 'number', array('required' => true))    
            ->add('direccion', 'text', array('required' => false))    
            ->add('telefono', 'text', array('required' => false))    
            ->add('celular', 'text', array('required' => false))    
            ->add('fax', 'text', array('required' => false))        
            ->add('email', 'text', array('required' => false))
            ->add('retencionFuenteVentas', 'text', array('required' => false))
            ->add('retencionFuenteVentasSinBase', 'text', array('required' => false))    
            ->add('autoretenedor', 'text', array('required' => false))
            ->add('contactoCliente', 'text', array('required' => false))
            ->add('celularContactoCliente', 'text', array('required' => false))
            ->add('contactoProveedor', 'text', array('required' => false))
            ->add('celularContactoProveedor', 'text', array('required' => false))
            ->add('codigoActividadEconomica', 'number', array('required' => false))
            ->add('porcentajeCREE', 'text', array('required' => false))
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
