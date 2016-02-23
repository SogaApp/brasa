<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class TurCotizacionDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('conceptoServicioRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurConceptoServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cs')
                    ->orderBy('cs.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('modalidadServicioRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurModalidadServicio',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ms')
                    ->orderBy('ms.codigoModalidadServicioPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('periodoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPeriodo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.codigoPeriodoPk', 'ASC');},
                'property' => 'nombre',
                'required' => true))                            
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd')) 
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd'))
            ->add('cantidad', 'number')
            ->add('lunes', 'checkbox', array('required'  => false))
            ->add('martes', 'checkbox', array('required'  => false))
            ->add('miercoles', 'checkbox', array('required'  => false))
            ->add('jueves', 'checkbox', array('required'  => false))
            ->add('viernes', 'checkbox', array('required'  => false))
            ->add('sabado', 'checkbox', array('required'  => false))
            ->add('domingo', 'checkbox', array('required'  => false))
            ->add('festivo', 'checkbox', array('required'  => false))
            ->add('dia31', 'checkbox', array('required'  => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

