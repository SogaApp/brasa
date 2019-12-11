<?php

namespace Brasa\AfiliacionBundle\Controller\Base;


use Brasa\AfiliacionBundle\BrasaAfiliacionBundle;
use Brasa\AfiliacionBundle\Entity\AfiCambioSalario;
use Brasa\AfiliacionBundle\Form\Type\AfiRazonSocialType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiClienteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RazonSocialController extends Controller
{
    var $strDqlLista = "";

    /**
     * @Route("/afi/base/razonSocial", name="brs_afi_base_razonSocial")
     */
    public function listaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
//        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 1)) {
//            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
//        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnEliminar')->isClicked()) {
                if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiRazonSocial')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_razonSocial'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }

        $arRazonesSocial = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/RazonSocial:lista.html.twig', array(
            'arRazonesSocial' => $arRazonesSocial,
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/razonSocial/nuevo/{codigoRazonSocial}", name="brs_afi_base_razonSocial_nuevo")
     */
    public function nuevoAction(Request $request, $codigoRazonSocial = '')
    {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRazonSocial = new \Brasa\AfiliacionBundle\Entity\AfiRazonSocial();
        if ($codigoRazonSocial != '' && $codigoRazonSocial != '0') {
//        if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 3)) {
//            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
//        }
            $arRazonSocial = $em->getRepository('BrasaAfiliacionBundle:AfiRazonSocial')->find($codigoRazonSocial);
        } else {
            if (!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
            }
        }
        $form = $this->createForm(new AfiRazonSocialType, $arRazonSocial);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arRazonSocial = $form->getData();
            $em->persist($arRazonSocial);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_afi_base_razonSocial'));

        }
        return $this->render('BrasaAfiliacionBundle:Base/RazonSocial:nuevo.html.twig', array(
            'arRazonSocial' => $arRazonSocial,
            'form' => $form->createView()));
    }


    private function lista()
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiRazonSocial')->listaDQL(
            $session->get('filtroRazonSocialNombre'),
            $session->get('filtroRazonSocialCodigo'),
            $session->get('filtroRazonSocialIndentificacion')
        );
    }

    private function filtrar($form)
    {
        $session = new Session();
        $session->set('filtroRazonSocialNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroRazonSocialCodigo', $form->get('TxtCodigo')->getData());
        $session->set('filtroRazonSocialIndentificacion', $form->get('TxtIdentificacion')->getData());
        $this->lista();
    }

    private function formularioFiltro()
    {
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', textType::class, array('label' => 'Nombre', 'data' => $session->get('filtroClienteNombre')))
            ->add('TxtIdentificacion', textType::class, array('label' => 'Identificacion', 'data' => $session->get('filtroClienteIndentificacion')))
            ->add('TxtCodigo', textType::class, array('label' => 'Codigo'))
            ->add('independiente', ChoiceType::class, array('choices' => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO')))
            ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }


    private function generarExcel()
    {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'P'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CÓDIG0')
            ->setCellValue('B1', 'NIT')
            ->setCellValue('C1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arRazonesSocial = $query->getResult();

        foreach ($arRazonesSocial as $arRazonSocial) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arRazonSocial->getCodigoRazonSocialPk())
                ->setCellValue('B' . $i, $arRazonSocial->getNit())
                ->setCellValue('C' . $i, $arRazonSocial->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Razon social');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Clientes.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }


}