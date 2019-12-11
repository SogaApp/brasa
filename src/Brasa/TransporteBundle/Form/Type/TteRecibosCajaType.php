<?php
namespace Brasa\TransporteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TteReciboCajaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder           
            ->add('vrFlete', 'text')
            ->add('vrManejo', 'text')
            ->add('guardar', 'submit');
    }

    public function getName()
    {
        return 'frmReciboCaja';
    }
}

