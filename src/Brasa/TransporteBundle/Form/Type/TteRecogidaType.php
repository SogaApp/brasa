<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteRecogidaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('anunciante', 'text')
            ->add('direccion', 'text')
            ->add('telefono', 'text')
            ->add('fechaAnuncio', 'datetime')    
            ->add('fechaRecogida', 'datetime')                
            ->add('unidades', 'text')
            ->add('pesoReal', 'text')
            ->add('pesoVolumen', 'text') 
            ->add('vrDeclarado', 'text')
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

