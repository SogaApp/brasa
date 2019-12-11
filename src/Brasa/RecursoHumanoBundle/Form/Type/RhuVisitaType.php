<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuVisitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuVisitaTipo',
                'property' => 'nombre',
            ))
            ->add('validarVencimiento', 'checkbox', array('required'  => false))    
            ->add('comentarios', 'textarea', array('required' => true, 'attr' => array('cols' => '5', 'rows' => '25')))
            ->add('fecha', 'datetime', array('required' => true, 'data' => new \DateTime('now')))
            ->add('fechaVence', 'date', array('required' => true, 'data' => new \DateTime('now')))
            ->add('nombreQuienVisita','text',array('required' => true))    
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

