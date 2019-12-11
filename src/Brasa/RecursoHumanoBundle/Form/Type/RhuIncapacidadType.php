<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuIncapacidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder                
            ->add('incapacidadTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuIncapacidadTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('it')
                    ->orderBy('it.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                            
            ->add('numeroEps', 'text', array('required' => true))   
            ->add('fechaDesde', 'date')                
            ->add('fechaHasta', 'date')
            ->add('estadoTranscripcion', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('estadoCobrar', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))
            ->add('estadoProrroga', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                            
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

