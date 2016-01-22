<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurServicioDetalleRecursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder                                                                                                  
            ->add('posicion', 'number')                           
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

