<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuRequisitoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                 
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'property' => 'nombre',
            ))             
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => false))                
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

