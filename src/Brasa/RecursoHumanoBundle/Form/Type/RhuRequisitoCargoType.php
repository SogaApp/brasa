<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuRequisitoCargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                        'property' => 'nombre',
            ))                 
            ->add('requisitoConceptoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRequisitoConcepto',
                'property' => 'nombre',
            ))                 
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

