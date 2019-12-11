<?php
namespace Brasa\TurnoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TurClientePuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('centroCostoContabilidadRel', EntityType::class, array(
                'class' => 'BrasaContabilidadBundle:CtbCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                 
            ->add('ciudadRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenCiudad',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))    
            ->add('programadorRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurProgramador',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('p')
                    ->orderBy('p.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('zonaRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurZona',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))  
            ->add('operacionRel', EntityType::class, array(
                'class' => 'BrasaTurnoBundle:TurOperacion',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('o')
                    ->orderBy('o.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('nombre', TextType::class, array('required'  => true))
            ->add('direccion', TextType::class, array('required'  => false))
            ->add('telefono', TextType::class, array('required'  => false))
            ->add('celular', TextType::class, array('required'  => false))
            ->add('contacto', TextType::class, array('required'  => false))
            ->add('telefonoContacto', TextType::class, array('required'  => false))
            ->add('celularContacto', TextType::class, array('required'  => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}

