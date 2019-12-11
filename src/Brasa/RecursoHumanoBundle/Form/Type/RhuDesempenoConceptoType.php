<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuDesempenoConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('nombre', 'text', array('required' => true))
            ->add('desempenoConceptoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDesempenoConceptoTipo',
                'property' => 'nombre',
            ))    
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

