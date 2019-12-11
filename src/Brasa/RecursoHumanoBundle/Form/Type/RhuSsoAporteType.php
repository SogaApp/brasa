<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuSsoAporteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingreso', 'text', array('required' => false))    
            ->add('retiro', 'text', array('required' => false))    
            ->add('ibcPension', 'number', array('required' => false))    
            ->add('ibcSalud', 'number', array('required' => false))    
            ->add('ibcRiesgosProfesionales', 'number', array('required' => false))                    
            ->add('ibcCaja', 'number', array('required' => false))                    
            ->add('diasCotizadosPension', 'number', array('required' => false))    
            ->add('diasCotizadosSalud', 'number', array('required' => false))    
            ->add('diasCotizadosRiesgosProfesionales', 'number', array('required' => false))                    
            ->add('diasCotizadosCajaCompensacion', 'number', array('required' => false))                    
            ->add('cotizacionPension', 'number', array('required' => false))    
            ->add('cotizacionSalud', 'number', array('required' => false))    
            ->add('cotizacionRiesgos', 'number', array('required' => false))                    
            ->add('cotizacionCaja', 'number', array('required' => false))                                    
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
