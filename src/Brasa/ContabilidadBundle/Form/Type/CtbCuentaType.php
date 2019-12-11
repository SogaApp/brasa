<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CtbCuentaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPadreFk', 'text', array('required' => true))    
            ->add('codigoCuentaPk', 'text', array('required' => true))    
            ->add('nombreCuenta', 'text', array('required' => true))    
            ->add('permiteMovimientos', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))
            ->add('exigeNit', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))
            ->add('exigeCentroCostos', 'choice', array('choices' => array('0' => 'NO' , '1' => 'SI')))                            
            ->add('porcentajeRetencion', 'number', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
