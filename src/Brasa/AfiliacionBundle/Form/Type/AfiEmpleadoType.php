<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AfiEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder    
            ->add('clienteRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiCliente',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))                 
            ->add('tipoIdentificacionRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ti')
                    ->orderBy('ti.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                                             
            ->add('estadoCivilRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ec')
                    ->orderBy('ec.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true)) 
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('rhRel', EntityType::class, array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRh',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('rh')
                    ->orderBy('rh.tipo', 'ASC');},
                'choice_label' => 'tipo',
                'required' => true))                             
            ->add('numeroIdentificacion', textType::class, array('required' => true))                            
            ->add('nombre1', textType::class, array('required' => true))
            ->add('nombre2', textType::class, array('required' => false))
            ->add('apellido1', textType::class, array('required' => true))
            ->add('apellido2', textType::class, array('required' => false))
            ->add('telefono', textType::class, array('required' => false))
            ->add('celular', textType::class, array('required' => false))
            ->add('direccion', textType::class, array('required' => false))
            ->add('barrio', textType::class, array('required' => false))
            ->add('codigoSexoFk', ChoiceType::class, array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))
            ->add('fechaNacimiento',DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date' )))                            
            ->add('correo', textType::class, array('required' => false))                            
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

