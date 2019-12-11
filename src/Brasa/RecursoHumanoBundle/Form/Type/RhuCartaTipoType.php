<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhuCartaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                                                                    
            ->add('nombre', TextType::class, array('required' => true))
            ->add('contenidoFormatoRel', EntityType::class, array(
                'class' => 'BrasaGeneralBundle:GenContenidoFormato',
                'choice_label' => 'titulo',
                'required' => false))    
            ->add('guardar', SubmitType::class);
    }
 
    public function getBlockPrefix()
    {
        return 'form';
    }
}
