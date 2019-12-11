<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuSsoPeriodoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anio', 'text', array('required' => true))
            ->add('mes', 'text', array('required' => true))
            ->add('anioPago', 'text', array('required' => true))
            ->add('mesPago', 'text', array('required' => true))    
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now'))) 
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaPago', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))    
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
