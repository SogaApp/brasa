<?php
namespace Brasa\AfiliacionBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AfiEntidadEntrenamientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder      
            ->add('nit', textType::class, array('required' => false))
            ->add('digitoVerificacion', textType::class, array('required' => false))                
            ->add('nombreCorto', textType::class, array('required' => true))                          
            ->add('direccion', textType::class, array('required' => false))  
            ->add('telefono', textType::class, array('required' => false))                              
            ->add('celular', textType::class, array('required' => false))                                          
            ->add('email', textType::class, array('required' => false))                              
            ->add('contacto', textType::class, array('required' => false))                  
            ->add('celularContacto', textType::class, array('required' => false))  
            ->add('telefonoContacto', textType::class, array('required' => false))  
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('guardarnuevo', SubmitType::class, array('label'  => 'Guardar y Nuevo'));
    }

    public function getName()
    {
        return 'form';
    }
}

