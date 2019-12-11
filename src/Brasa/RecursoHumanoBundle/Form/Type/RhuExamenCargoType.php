<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuExamenCargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                        'property' => 'nombre',
            ))                 
            ->add('examenTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenTipo',
                'property' => 'nombre',
            ))                 
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

