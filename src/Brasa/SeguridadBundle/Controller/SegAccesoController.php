<?php

namespace Brasa\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Brasa\SeguridadBundle\Form\Type\UserType;

class SegAccesoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/seg/error/permiso/especial", name="brs_seg_error_permiso_especial")
     */
    public function errorPermisoEspecialAction()
    {
        return $this->render('BrasaSeguridadBundle:Acceso:errorPermisoEspecial.html.twig');
    }
}