<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurRecursoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('recursoTipoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurRecursoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('rt')
                    ->orderBy('rt.codigoRecursoTipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))  
            ->add('recursoGrupoRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurRecursoGrupo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('rg')
                    ->orderBy('rg.codigoRecursoGrupoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                                                          
            ->add('numeroIdentificacion', TextType::class, array('required' => true))                             
            ->add('nombreCorto', TextType::class, array('required' => true))
            ->add('apodo', TextType::class, array('required' => false))    
            ->add('telefono', TextType::class, array('required' => false))
            ->add('celular', TextType::class, array('required' => false))
            ->add('direccion', TextType::class, array('required' => false))
            ->add('correo', TextType::class, array('required' => false))
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('codigoTurnoFijoNominaFk', TextType::class, array('required' => false))  
            ->add('codigoTurnoFijoDescansoFk', TextType::class, array('required' => false))  
            ->add('codigoTurnoFijo31Fk', TextType::class, array('required' => false))  
            ->add('BtnActualizar', SubmitType::class, array('label'  => 'Actualizar datos desde empleado RH'))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
                    
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

