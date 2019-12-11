<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuCapacitacionDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                            
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('nombreCorto', 'text', array('required' => false))
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

