<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuProgramacionPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder             
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('pagoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuPagoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('pt')
                    ->orderBy('pt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                                         
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd'))                
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd'))
            ->add('fechaHastaReal', 'date', array('format' => 'yyyyMMdd'))
            ->add('dias', 'number', array('required' => true)) 
            ->add('mensajePago', 'textarea', array('required' => false))                                            
            ->add('guardar', 'submit');        
    }
 
    public function getName()
    {
        return 'form';
    }
}

