<?php
namespace Brasa\GeneralBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GenCuentaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bancoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenBanco',
                        'property' => 'nombre',))                
            ->add('nombre', 'text', array('required' => true))
            ->add('cuenta', 'text', array('required' => true))
            ->add('tipo', 'text', array('required' => true))
            ->add('codigoCuentaFk', 'text', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
