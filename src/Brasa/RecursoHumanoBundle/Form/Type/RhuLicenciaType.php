<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuLicenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder                  
            ->add('licenciaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuLicenciaTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('lt')
                    ->orderBy('lt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                            
            ->add('fechaDesde', 'date')                
            ->add('fechaHasta', 'date')  
            ->add('afectaTransporte', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                                            
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

