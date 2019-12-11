<?php
namespace Brasa\CarteraBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


class SaldoFavorController extends Controller
{

    var $strListaDql = "";
    /**
     * @Route("/cartera/consulta/saldofavor/resumen/", name="brs_cartera_consulta_saldofavor_resumen")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $this->lista();

        $arSaldosFavor = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/SaldoFavor:lista.html.twig',array(
            'arSaldosFavor' => $arSaldosFavor
        ));
    }

    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarSaldoFavor')->listaConsultaDql();
    }

}