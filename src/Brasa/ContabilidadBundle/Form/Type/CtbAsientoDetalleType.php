<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CtbAsientoDetalleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbCuenta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCuenta', 'ASC');},
                'property' => 'nombreCuenta',
                'required' => true))
            ->add('terceroRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbTercero',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true)) 
            ->add('asientoTipoRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbAsientoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                    ->orderBy('a.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                
            ->add('fecha', 'date', array('required' => true))
            ->add('valorBase', 'text', array('required' => true))
            ->add('debito', 'text', array('required' => true))
            ->add('credito', 'text', array('required' => true))
            ->add('soporte', 'text', array('required' => true))
            ->add('documentoReferente', 'text', array('required' => true))
            ->add('plazo', 'text', array('required' => true))
            ->add('descripcion', 'textarea', array('required' => false))
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'))
            ->add('BtnGuardarNuevo', 'submit', array('label' => 'Guardar y nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}
