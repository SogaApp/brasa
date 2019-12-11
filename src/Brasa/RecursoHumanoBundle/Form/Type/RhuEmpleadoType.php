<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuEmpleadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('puestoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurPuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                  
            ->add('centroCostoContabilidadRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                
            ->add('tipoIdentificacionRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenTipoIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ti')
                    ->orderBy('ti.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('bancoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuBanco',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('b')
                    ->orderBy('b.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('estadoCivilRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'property' => 'nombre',
            ))
            ->add('ciudadExpedicionRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('ciudadRel', 'entity', array(
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
            ->add('subzonaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSubzona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sz')
                    ->orderBy('sz.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('empleadoTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                    ->orderBy('et.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                            
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO')))
            ->add('fechaNacimiento','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaExpedicionIdentificacion','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('nombre1', 'text', array('required' => true))
            ->add('codigoTipoLibreta', 'choice', array('choices' => array('1' => '1° CLASE', '2' => '2° CLASE', '0' => 'NO APLICA')))
            ->add('nombre2', 'text', array('required' => false))
            ->add('apellido1', 'text', array('required' => true))
            ->add('apellido2', 'text', array('required' => false))
            ->add('telefono', 'text', array('required' => false))
            ->add('celular', 'text', array('required' => false))
            ->add('direccion', 'text', array('required' => false))
            ->add('barrio', 'text', array('required' => true))
            ->add('rhRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuRh',
                'property' => 'tipo',
            ))
            ->add('correo', 'text', array('required' => false))
            ->add('cuenta', 'text', array('required' => false))
            ->add('tipoCuenta', 'choice', array('choices' => array('S' => 'AHORRO', 'D' => 'CORRIENTE', 'DP' => 'DAVIPLATA')))
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('auxilioTransporte', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('discapacidad', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))                
            ->add('padreFamilia', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('pagadoEntidadSalud', 'choice', array('choices'   => array('1' => 'SI', '0' => 'NO')))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('camisa', 'text', array('required' => false))
            ->add('jeans', 'text', array('required' => false))
            ->add('calzado', 'text', array('required' => false))
            ->add('empleadoEstudioTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'property' => 'nombre',
            ))
            ->add('horarioRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuHorario',
                'property' => 'nombre',
            ))
            ->add('departamentoEmpresaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'property' => 'nombre',
            ))            
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

