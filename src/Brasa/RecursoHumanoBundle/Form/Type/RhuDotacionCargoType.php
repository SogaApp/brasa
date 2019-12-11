<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuDotacionCargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                        'property' => 'nombre',
            ))                 
            ->add('dotacionElementoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDotacionElemento',
                'property' => 'dotacion',
            ))
            ->add('cantidadAsignada', 'number', array('required' => true))    
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

