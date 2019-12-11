<?php

namespace Brasa\AfiliacionBundle\Controller\Proceso;

use Brasa\GeneralBundle\MisClases\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ActualizarClienteAdministracionController extends Controller
{

    /**
     * @Route("/afi/proceso/actualizar/clienteadministracion", name="brs_afi_proceso_actualizar_clienteadministracion")
     */
    public function listaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new Mensajes();
        $form = $this->formulario();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnActualizar')->isClicked()) {
                $arAfiClientes = $em->getRepository("BrasaAfiliacionBundle:AfiCliente")->findAll();
                foreach ($arAfiClientes as $arAfiCliente) {
                    $vrAdministracionActual = $arAfiCliente->getAdministracion();
                    $vrAdministracionNuevo = $vrAdministracionActual + $form->get('vrAdministracion')->getData();
                    $arAfiCliente->setAdministracion($vrAdministracionNuevo);
                    $em->persist($arAfiCliente);
                }
                $objMensaje->Mensaje('informacion', 'Se actualizaron correctamente los valores', $this);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_proceso_actualizar_clienteadministracion'));
            }
        }
        return $this->render('BrasaAfiliacionBundle:Proceso:actualizarClienteAdministracion.html.twig', array(
            'form' => $form->createView()));
    }

    private function formulario()
    {
        $form = $this->createFormBuilder()
            ->add('vrAdministracion', NumberType::class, array('label' => 'Administracion', 'data' => 0))
            ->add('BtnActualizar', SubmitType::class, array('label' => 'Actualizar'))
            ->getForm();
        return $form;
    }

}