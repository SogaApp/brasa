<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuDisciplinarioTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('nombre', 'text', array('required' => true))
            ->add('contenidoFormatoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenContenidoFormato',
                'property' => 'titulo',
                'required' => false))    
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}
