<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TtePrecioDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                             
            //->add('nombre', 'text', array('required' => true))
            //->add('fechaVencimiento', 'date', array('required' => true))
            ->add('ciudadDestinoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'property' => 'nombre',
            ))
            ->add('productoRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteProducto',
                'property' => 'nombre',
            ))
            ->add('vrKilo', 'text', array('required' => false))    
            ->add('vrUnidad', 'text', array('required' => false))    
            ->add('ctKilosLimite', 'text', array('required' => false))    
            ->add('vrKilosLimite', 'text', array('required' => false))        
            ->add('vrKiloAdicional', 'text', array('required' => false))        
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y nuevo'));
    }
 
    public function getName()
    {
        return 'form';
    }
}

