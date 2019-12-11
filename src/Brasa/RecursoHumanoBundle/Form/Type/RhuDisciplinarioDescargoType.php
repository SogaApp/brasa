<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuDisciplinarioDescargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                  
            ->add('fecha', 'date', array('required' => true, 'data' => new \DateTime('now')))
            ->add('descargo', 'textarea', array('required' => false))
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

