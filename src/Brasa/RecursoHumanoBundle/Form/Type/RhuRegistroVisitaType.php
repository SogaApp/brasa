<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuRegistroVisitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            
            ->add('departamentoEmpresaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'property' => 'nombre',
                'required' => true))   
            ->add('motivo', 'text', array('required' => false))        
            ->add('codigoEscarapela', 'text', array('required' => false))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('buscar', 'submit')    
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'form';
    }
}

