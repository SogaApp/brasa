<?php
namespace Brasa\GeneralBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GenContenidoFormatoSecundarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('titulo', 'text', array('required' => true))
            ->add('codigoFormatoIso', 'text', array('required' => false))
            ->add('version', 'text', array('required' => false))
            ->add('fechaVersion','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('contenido', 'textarea', array('required' => true))                                
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}
