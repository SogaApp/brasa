<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmbargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder   
            ->add('embargoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmbargoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                 
            ->add('numero', 'text', array('required' => false))   
            ->add('valor', 'number', array('required' => true))   
            ->add('porcentaje', 'number', array('required' => false))                               
            ->add('valorFijo', 'checkbox', array('required'  => false))                
            ->add('porcentajeDevengado', 'checkbox', array('required'  => false))                
            ->add('porcentajeDevengadoPrestacional', 'checkbox', array('required'  => false))                
            ->add('porcentajeDevengadoMenosDescuentoLey', 'checkbox', array('required'  => false))                
            ->add('porcentajeExcedaSalarioMinimo', 'checkbox', array('required'  => false))                                            
            ->add('estadoActivo', 'checkbox', array('required'  => false))                
            ->add('partesExcedaSalarioMinimo', 'checkbox', array('required'  => false))                                            
            ->add('partes', 'number', array('required' => false))                                                           
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

