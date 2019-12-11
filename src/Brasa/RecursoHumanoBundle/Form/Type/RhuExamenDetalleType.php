<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuExamenDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                    
            ->add('fechaVence', 'date') 
            ->add('validarVencimiento', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                                                            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit');
    }
 
    public function getName()
    {
        return 'form';
    }
}
