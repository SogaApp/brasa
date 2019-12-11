<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RhuSsoPeriodoDetalleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ssoSucursalRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSsoSucursal',
                'property' => 'nombre',
            ))
            ->add('detalle', 'text', array('required' => false))    
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
