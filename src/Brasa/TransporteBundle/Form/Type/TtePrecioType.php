<<<<<<< HEAD
<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TtePrecioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                             
            ->add('nombre', 'text', array('required' => true))
            ->add('fechaVencimiento', 'date', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }
 
    public function getName()
    {
        return 'form';
    }
}

=======
<?php
namespace Brasa\TransporteBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TtePrecioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                                             
            ->add('nombre', 'text', array('required' => true))
            ->add('fechaVencimiento', 'date', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'));
    }
 
    public function getName()
    {
        return 'form';
    }
}

>>>>>>> 30fa032eaa121a2a644bd8fdcc615fe16e877a7d
