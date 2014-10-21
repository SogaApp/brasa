<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteGuiasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoServicioRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteTiposServicio',
                'property' => 'nombre',
            ))
            ->add('tipoPagoRel', 'entity', array(
                'class' => 'BrasaTransporteBundle:TteTiposPago',
                'property' => 'nombre',
            ))                                      
            
            ->add('terceroRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenTerceros',
                'property' => 'nombreCorto',
            ))       
            ->add('ciudadDestinoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudades',
                'property' => 'nombre',
            ))                
            ->add('documentoCliente', 'text', array('required' => false))                        
            ->add('nombreDestinatario', 'text')                        
            ->add('telefonoDestinatario', 'text', array('required' => false))
            ->add('direccionDestinatario', 'text')
            ->add('ctUnidades', 'text')
            ->add('ctPesoReal', 'text')
            ->add('ctPesoVolumen', 'text')
            ->add('vrFlete', 'text')
            ->add('vrManejo', 'text')
            ->add('vrDeclarado', 'text')                
            ->add('vrRecaudo', 'text')
            ->add('contenido', 'textarea', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

