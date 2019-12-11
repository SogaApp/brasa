<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuSeleccionRequisitoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('estadoCivilRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEstadoCivil',
                'property' => 'nombre',
                'required' => false
            ))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false))
            ->add('estudioTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo',
                'property' => 'nombre',
            ))
            ->add('experienciaRequisicionRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuSeleccionRequisicionExperiencia',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ;},
                'property' => 'nombre',
                'required' => true))   
            ->add('zonaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))                             
            ->add('codigoDisponibilidadFk', 'choice', array('choices'   => array('1' => 'TIEMPO COMPLETO', '2' => 'MEDIO TIEMPO', '3' => 'POR HORAS','4' => 'DESDE CASA', '5' => 'PRACTICAS', '0' => 'NO APLICA')))
            //->add('codigoExperienciaFk', 'choice', array('choices'   => array('1' => '1 AÑO', '2' => '2 AÑOS', '3' => '3-4 AÑOS','4' => '5-10 AÑOS', '5' => 'GRADUADO', '6' => 'SIN EXPERIENCIA')))
            ->add('codigoSexoFk', 'choice', array('choices'   => array('M' => 'MASCULINO', 'F' => 'FEMENINO', 'I' => 'INDIFERENTE')))
            ->add('codigoTipoVehiculoFk', 'choice', array('choices'   => array('1' => 'CARRO', '2' => 'MOTO', '0' => 'NO APLICA')))
            ->add('codigoLicenciaCarroFk', 'choice', array('choices'   => array('1' => 'SI', '2' => 'NO', '0' => 'NO APLICA')))
            ->add('codigoLicenciaMotoFk', 'choice', array('choices'   => array('1' => 'SI', '2' => 'NO', '0' => 'NO APLICA')))
            ->add('nombre', 'text', array('required' => true))
            ->add('numeroHijos', 'number', array('required' => false))
            ->add('edadMinima', 'text', array('required' => false))
            ->add('edadMaxima', 'text', array('required' => false))
            ->add('codigoReligionFk', 'choice', array('choices'   => array('1' => 'CATOLICO', '2' => 'CRISTIANO', '3' => 'PROTESTANTE', '4' => 'INDIFERENTE')))
            ->add('cantidadSolicitada', 'number', array('label' => 'Cantidad Solicitada', 'required' => true))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

