<?php
namespace Brasa\InventarioBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class InvItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('marcaRel', 'entity', array(
                'class' => 'BrasaInventarioBundle:InvMarca',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                    ->orderBy('m.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                 
            ->add('nombre', 'text', array('required' => true))            
            ->add('porcentajeIva', 'number', array('required' => false))
            ->add('vrCostoPredeterminado', 'number', array('required' => false))                                                                        
            ->add('guardar', 'submit')            
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

