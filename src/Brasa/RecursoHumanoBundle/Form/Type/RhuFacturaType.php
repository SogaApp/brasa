<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuFacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                          
            ->add('terceroRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenTercero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))
            ->add('centroCostoRel', 'entity', array(
            'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                ->orderBy('cc.nombre', 'ASC');},
            'property' => 'nombre',
            'required' => true))          
            ->add('comentarios', 'textarea', array('required' => false))                                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

