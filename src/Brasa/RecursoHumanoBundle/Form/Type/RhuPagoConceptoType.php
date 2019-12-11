<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuPagoConceptoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required' => true))
            ->add('componeSalario', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('componePorcentaje', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('componeValor', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('porPorcentaje', 'number', array('required' => true))
            ->add('prestacional', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('generaIngresoBasePrestacion', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('generaIngresoBaseCotizacion', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('operacion', 'number', array('required' => true))
            ->add('conceptoAdicion', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('conceptoIncapacidad', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('conceptoAuxilioTransporte', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('codigoCuentaFk', 'text', array('required' => true))
            ->add('tipoCuenta', 'number', array('required' => true))    
            ->add('codigoCuentaOperacionFk', 'text', array('required' => true))
            ->add('codigoInterface', 'text', array('required' => false))                
            ->add('tipoCuentaOperacion', 'number', array('required' => true))                    
            ->add('conceptoPension', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('conceptoSalud', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('provisionIndemnizacion', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('provisionVacacion', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('tipoAdicional', 'number', array('required' => true))
            ->add('codigoCuentaComercialFk', 'text', array('required' => true))
            ->add('tipoCuentaComercial', 'number', array('required' => true))
            ->add('guardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
