<?php
namespace Brasa\RecursoHumanoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class RhuPagoAdicionalPeriodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder               
            ->add('fecha', 'date', array('format' => 'yyyyMMdd'))
            ->add('guardar', 'submit');            
    }

    public function getName()
    {
        return 'form';
    }
}

