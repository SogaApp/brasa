<?php
namespace Brasa\ContabilidadBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CtbTerceroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoIdentificacionRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ti')
                    ->orderBy('ti.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('digitoVerificacion', 'number', array('required' => false))
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('nombre1', 'text', array('required' => false))
            ->add('nombre2', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => false))    
            ->add('apellido2', 'text', array('required' => false))
            ->add('razonSocial', 'text', array('required' => false))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))   
            ->add('direccion', 'text', array('required' => false))    
            ->add('telefono', 'text', array('required' => false))    
            ->add('celular', 'text', array('required' => false))    
            ->add('fax', 'text', array('required' => false))        
            ->add('email', 'text', array('required' => false))
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'));
    }

    public function getName()
    {
        return 'form';
    }
}
