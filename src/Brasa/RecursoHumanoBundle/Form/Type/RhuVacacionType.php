<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuVacacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('diasDisfrutados', 'number', array('required' => true))                 
            ->add('diasPagados', 'number', array('required' => true))                 
            ->add('vrSalarioPromedioPropuesto', 'number', array('required' => false))                 
            ->add('fechaDesdeDisfrute', 'date')
            ->add('fechaHastaDisfrute', 'date')               
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit');
    }
 
    public function getName()
    {
        return 'form';
    }
}

