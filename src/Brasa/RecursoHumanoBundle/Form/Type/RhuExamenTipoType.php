<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuExamenTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('nombre', 'text', array('required' => true))  
            ->add('ingreso', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}
