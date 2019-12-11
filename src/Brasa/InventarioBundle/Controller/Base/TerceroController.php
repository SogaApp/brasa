<?php
namespace Brasa\InventarioBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\InventarioBundle\Form\Type\InvTerceroType;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class TerceroController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    var $strNit = "";

    /**
     * @Route("/inv/base/tercero/", name="brs_inv_base_tercero")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 135, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoTerceroPk) {
                            $arTercero = new \Brasa\InventarioBundle\Entity\InvTercero();
                            $arTercero = $em->getRepository('BrasaInventarioBundle:InvTercero')->find($codigoTerceroPk);
                            $em->remove($arTercero);
                        }
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_inv_base_tercero'));
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el tercero porque esta siendo utilizado', $this);
                      }
                }
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }

        $arTerceros = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Base/Tercero:lista.html.twig', array(
            'arTerceros' => $arTerceros,
            'form' => $form->createView()));
    }

    /**
     * @Route("/inv/base/tercero/nuevo/{codigoTercero}", name="brs_inv_base_tercero_nuevo")
     */

    public function nuevoAction(Request $request, $codigoTercero = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arTercero = new \Brasa\InventarioBundle\Entity\InvTercero();
        if($codigoTercero != '' && $codigoTercero != '0') {
            $arTercero = $em->getRepository('BrasaInventarioBundle:InvTercero')->find($codigoTercero);
        }
        $form = $this->createForm(new InvTerceroType, $arTercero);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arTercero = $form->getData();
            $em->persist($arTercero);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_inv_base_tercero_nuevo', array('codigoTercero' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_inv_base_tercero'));
            }
        }
        return $this->render('BrasaInventarioBundle:Base/Tercero:nuevo.html.twig', array(
            'arTercero' => $arTercero,
            'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvTercero')->listaDQL(
                $this->strNit,
                $this->strNombre
                );
    }

    private function filtrar ($form) {
        $this->strNit = $form->get('TxtNit')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $this->strNit))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
        for($col = 'A'; $col !== 'O'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'DV')
                    ->setCellValue('D1', 'NOMBRE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arTerceros = new \Brasa\InventarioBundle\Entity\InvTercero();
        $arTerceros = $query->getResult();

        foreach ($arTerceros as $arTercero) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTercero->getCodigoTerceroPk())
                    ->setCellValue('B' . $i, $arTercero->getNit())
                    ->setCellValue('C' . $i, $arTercero->getDigitoVerificacion())
                    ->setCellValue('D' . $i, $arTercero->getNombreCorto());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Tercero');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Terceros.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

}