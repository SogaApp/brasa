<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuDotacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder                                            
            ->add('dotacionTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDotacionTipo',
                        'property' => 'nombre',
            ))
            ->add('fechaEntrega', 'date')                
            ->add('codigoInternoReferencia', 'number', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false))              
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

