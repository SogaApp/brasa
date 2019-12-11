<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmpleadoInformacionInternaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('required' => true))
            ->add('accion', 'choice', array('choices' => array('1' => 'BLOQUEADO', '0' => 'DESBLOQUEADO')))    
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

