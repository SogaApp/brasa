<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuExamenRestriccionMedicaEditarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('examenRevisionMedicaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuExamenRevisionMedicaTipo',
                'property' => 'nombre',
            ))
            ->add('dias', 'number', array('required' => true))    
            //->add('comentarios', 'textarea', array('required' => false))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}

