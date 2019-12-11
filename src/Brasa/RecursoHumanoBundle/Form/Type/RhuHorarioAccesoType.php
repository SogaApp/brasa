<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuHorarioAccesoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder             
            ->add('fechaEntrada', 'datetime', array('format' => 'yyyyMMdd'))                            
            ->add('fechaSalida', 'datetime', array('format' => 'yyyyMMdd'))                            
            ->add('estadoEntrada', 'checkbox', array('required'  => false))                                              
            ->add('entradaTarde', 'checkbox', array('required'  => false))                                              
            ->add('estadoSalida', 'checkbox', array('required'  => false))                                        
            ->add('salidaAntes', 'checkbox', array('required'  => false))                                        
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

