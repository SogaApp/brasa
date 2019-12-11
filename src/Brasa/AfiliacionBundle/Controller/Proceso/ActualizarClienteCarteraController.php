<?php
namespace Brasa\AfiliacionBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class ActualizarClienteCarteraController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/proceso/actualizar/clientecartera", name="brs_afi_proceso_actualizar_clientecartera")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 105)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if($form->get('BtnActualizar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoClientePk) {
                        $arClienteAfiliacion = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                        $arClienteAfiliacion = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigoClientePk);
                        $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                        $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $arClienteAfiliacion->getNit()));
                        if ($arClienteCartera == null){
                            $arClienteCartera = new \Brasa\CarteraBundle\Entity\CarCliente();
                            $arClienteCartera->setFormaPagoRel($arClienteAfiliacion->getFormaPagoRel());
                            $arClienteCartera->setAsesorRel($arClienteAfiliacion->getAsesorRel());
                            $arClienteCartera->setCiudadRel($arClienteAfiliacion->getCiudadRel());
                            $arClienteCartera->setNit($arClienteAfiliacion->getNit());
                            $arClienteCartera->setDigitoVerificacion($arClienteAfiliacion->getDigitoVerificacion());
                            $arClienteCartera->setNombreCorto($arClienteAfiliacion->getNombreCorto());
                            $arClienteCartera->setPlazoPago($arClienteAfiliacion->getPlazoPago());
                            $arClienteCartera->setDireccion($arClienteAfiliacion->getDireccion());
                            $arClienteCartera->setTelefono($arClienteAfiliacion->getTelefono());
                            $arClienteCartera->setCelular($arClienteAfiliacion->getCelular());
                            $arClienteCartera->setFax($arClienteAfiliacion->getFax());
                            $arClienteCartera->setEmail($arClienteAfiliacion->getEmail());
                            $em->persist($arClienteCartera);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_afi_proceso_actualizar_clientecartera'));
                }
            }
        }
        if ($form->get('BtnFiltrar')->isClicked()) {
            $this->filtrar($form);
        }
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 150);
        return $this->render('BrasaAfiliacionBundle:Proceso:lista.html.twig', array(
            'arClientes' => $arClientes,
            'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->listaDQL(
                $session->get('filtroClienteNombre'),
                '',
                $session->get('filtroClienteIdentificacion'),
                $session->get('filtroIndependiente')
                );
    }

    private function filtrar ($form) {
        $session = new session;
        $session->set('filtroClienteNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroClienteIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroIndependiente', $form->get('independiente')->getData());
        $this->lista();
    }

    private function formularioFiltro() {
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroClienteNombre')))
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroClienteIdentificacion')))
            ->add('independiente', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroIndependiente')))
            ->add('BtnActualizar', SubmitType::class, array('label'  => 'Actualizar',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

}