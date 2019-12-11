<?php

namespace Brasa\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class SeguridadController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);                        
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return $this->render('BrasaSeguridadBundle:Seguridad:login.html.twig', array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
    
    public function registroAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {            
            $arrControles = $request->request->All();
            $arUsuario = new \Brasa\SeguridadBundle\Entity\User();            
            $factory = $this->get('security.encoder_factory');                        
            $encoder = $factory->getEncoder($arUsuario);            
            $password = $encoder->encodePassword($arrControles['TxtPassword'], $arUsuario->getSalt());
            $arUsuario->setPassword($password);                        
            $arUsuario->setUsername($arrControles['TxtUsuario']);
            $arUsuario->setNombreCorto($arrControles['TxtNombreCorto']);
            $arUsuario->setEmail($arrControles['TxtUsuario']);            
            $em->persist($arUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('login'));
        }
        return $this->render('BrasaSeguridadBundle:Seguridad:registro.html.twig');
    }
    
}