<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuCreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('creditoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCreditoTipo',
                'property' => 'nombre',
                'required' => true,
            ))
             ->add('creditoTipoPagoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCreditoTipoPago',
                'property' => 'nombre',
                'required' => true,
            ))
            ->add('vrPagar', 'number', array('required' => true))                                                                           
            ->add('numeroCuotas', 'number', array('required' => true))
            ->add('vrCuota', 'number', array('required' => true))                                                                                           
            ->add('vrCuotaPrima', 'number', array('required' => true))                                                                                           
            ->add('fechaInicio', 'date', array('required' => true))    
            ->add('fechaCredito', 'date', array('required' => true))    
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('seguro', 'number', array('required' => true))
            ->add('numeroLibranza', 'text', array('required' => false))
            ->add('validarCuotas', 'checkbox', array('required'  => false))
            ->add('aplicarCuotaPrima', 'checkbox', array('required'  => false))
            ->add('numeroCuotaActual', 'number', array('required' => false))    
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

