<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuAspiranteType extends AbstractType
{
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
            ->add('estadoCivilRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'property' => 'nombre',
                'required' => true
            ))
            ->add('rhRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRh',
                'property' => 'tipo',
                'required' => true
            ))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('ciudadExpedicionRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('ciudadNacimientoRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('zonaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                            
            ->add('fechaNacimiento','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('nombre1', 'text', array('required' => true))
            ->add('nombre2', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => true))
            ->add('apellido2', 'text', array('required' => false))
            ->add('correo', 'text', array('required' => false))
            ->add('telefono', 'text', array('required' => false))
            ->add('codigoTipoLibreta', 'choice', array('choices' => array('1' => '1° CLASE', '2' => '2° CLASE', '0' => 'NO APLICA')))                
            ->add('celular', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))
            ->add('barrio', 'text', array('required' => false)) 
            ->add('peso', 'text', array('required' => false))                
            ->add('estatura', 'text', array('required' => false))                
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))
            ->add('codigoDisponibilidadFk', 'choice', array('choices'   => array('1' => 'TIEMPO COMPLETO', '2' => 'MEDIO TIEMPO', '3' => 'POR HORAS','4' => 'DESDE CASA', '5' => 'PRACTICAS')))                            
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('cargoAspira', 'text', array('required' => false))                
            ->add('recomendado', 'text', array('required' => false))                
            ->add('operacion', 'text', array('required' => false))
            ->add('reintegro', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

