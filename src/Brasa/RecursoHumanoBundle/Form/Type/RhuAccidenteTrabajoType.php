<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
class RhuAccidenteTrabajoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('ciudadRel', 'entity', array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true))
            ->add('codigoFurat', 'number', array('required' => true))
            ->add('fechaAccidente','date',array('required' => true ,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('tipoAccidenteRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoAccidente',
                        'property' => 'nombre',))
            ->add('fechaEnviaInvestigacion', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaIncapacidadDesde', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaIncapacidadHasta', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('dias', 'number', array('required' => false))
            ->add('cie10', 'text', array('required' => false))
            ->add('diagnostico', 'text', array('required' => false))
            ->add('naturalezaLesion', 'text', array('required' => false))
            ->add('cuerpoAfectado', 'text', array('required' => false))
            ->add('agente', 'text', array('required' => false))
            ->add('mecanismoAccidente', 'text', array('required' => false))
            ->add('lugarAccidente', 'text', array('required' => false))
            ->add('coordinadorEncargado', 'text', array('required' => false))                
            ->add('cargoCoordinadorEncargado', 'text', array('required' => false))                                
            ->add('tiempoServicioEmpleado', 'text', array('required' => false))                                
            ->add('tareaDesarrolladaMomentoAccidente', 'choice', array('choices' => array('1' => 'SI', '0' => 'NO')))                
            ->add('oficioHabitual', 'text', array('required' => false))                                
            ->add('descripcionAccidente', 'textarea', array('required' => false))
            ->add('actoInseguro', 'textarea', array('required' => false))
            ->add('condicionInsegura', 'textarea', array('required' => false))
            ->add('factorPersonal', 'textarea', array('required' => false))
            ->add('factorTrabajo', 'textarea', array('required' => false))
            ->add('planAccion1', 'textarea', array('required' => false))
            ->add('tipoControlUnoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
                        'property' => 'nombre',))
            ->add('fechaVerificacion1', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable1', 'text', array('required' => false))
            ->add('planAccion2', 'textarea', array('required' => false))
            ->add('tipoControlDosRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
                        'property' => 'nombre',))
            ->add('fechaVerificacion2', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable2', 'text', array('required' => false))                
            ->add('planAccion3', 'textarea', array('required' => false))
            ->add('tipoControlTresRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
                        'property' => 'nombre',))
            ->add('fechaVerificacion3', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('areaResponsable3', 'text', array('required' => false))
            ->add('participanteInvestigacion1', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion1', 'text', array('required' => false))
            ->add('participanteInvestigacion2', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion2', 'text', array('required' => false))                
            ->add('participanteInvestigacion3', 'text', array('required' => false))                
            ->add('cargoParticipanteInvestigacion3', 'text', array('required' => false))                
            ->add('representanteLegal', 'text', array('required' => false))                
            ->add('cargoRepresentanteLegal', 'text', array('required' => false))
            ->add('licencia', 'text', array('required' => false))
            ->add('fechaVerificacion', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('responsableVerificacion', 'text', array('required' => false))                
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }

    public function getName()
    {
        return 'form';
    }
}
