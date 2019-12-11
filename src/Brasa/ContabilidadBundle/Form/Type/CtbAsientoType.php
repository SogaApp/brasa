<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CtbAsientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comprobanteRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('numeroAsiento', 'text', array('required' => true))
            ->add('soporte', 'text', array('required' => true))
            ->add('fecha', 'date', array('required' => true))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'))
            ->add('BtnGuardarNuevo', 'submit', array('label' => 'Guardar y nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}
