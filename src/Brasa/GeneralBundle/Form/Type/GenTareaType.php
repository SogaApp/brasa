<?php
namespace Brasa\GeneralBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class GenTareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder    
            ->add('usuarioTareaFk', 'entity', array(
                'class' => 'BrasaSeguridadBundle:User',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.nombreCorto', 'ASC');},
                'property' => 'nombreCorto',
                'required' => true))                 
            ->add('asunto', 'text', array('required' => true))               
            ->add('fechaProgramada', 'date', array('format' => 'yyyyMMdd'))                
            ->add('hora', 'time')
            ->add('comentarios', 'textarea', array('required' => false))                
            ->add('guardar', 'submit')
            ->add('guardarnuevo', 'submit', array('label'  => 'Guardar y Nuevo'));        
    }
 
    public function getName()
    {
        return 'form';
    }
}

