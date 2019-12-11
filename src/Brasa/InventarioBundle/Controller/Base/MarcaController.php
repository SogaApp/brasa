<?php
namespace Brasa\InventarioBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\InventarioBundle\Form\Type\InvMarcaType;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class MarcaController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";    

    /**
     * @Route("/inv/base/marca/", name="brs_inv_base_marca")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 133, 1)) {
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
                        foreach ($arrSeleccionados AS $codigoMarcaPk) {
                            $arMarca = new \Brasa\InventarioBundle\Entity\InvMarca();
                            $arMarca = $em->getRepository('BrasaInventarioBundle:InvMarca')->find($codigoMarcaPk);
                            $em->remove($arMarca);
                        }
                        $em->flush();
                        return $this->redirect($this->generateUrl('brs_inv_base_marca'));
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el marca porque esta siendo utilizado', $this);
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

        $arMarcas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaInventarioBundle:Base/Marca:lista.html.twig', array(
            'arMarcas' => $arMarcas,
            'form' => $form->createView()));
    }

    /**
     * @Route("/inv/base/marca/nuevo/{codigoMarca}", name="brs_inv_base_marca_nuevo")
     */

    public function nuevoAction(Request $request, $codigoMarca = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arMarca = new \Brasa\InventarioBundle\Entity\InvMarca();
        if($codigoMarca != '' && $codigoMarca != '0') {
            $arMarca = $em->getRepository('BrasaInventarioBundle:InvMarca')->find($codigoMarca);
        }
        $form = $this->createForm(new InvMarcaType, $arMarca);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arMarca = $form->getData();
            $em->persist($arMarca);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_inv_base_marca_nuevo', array('codigoMarca' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_inv_base_marca'));
            }
        }
        return $this->render('BrasaInventarioBundle:Base/Marca:nuevo.html.twig', array(
            'arMarca' => $arMarca,
            'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaInventarioBundle:InvMarca')->listaDQL(
                $this->strNombre,
                $this->strCodigo
                );
    }

    private function filtrar ($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->lista();
    }

    private function formularioFiltro() {
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))
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
        for($col = 'C'; $col !== 'F'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
                }
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'COSTO PREDETERMINADO')
                    ->setCellValue('D1', 'COSTO PROMEDIO')
                    ->setCellValue('E1', 'PRECIO PREDETERMINADO')
                    ->setCellValue('F1', '% IVA')
                    ->setCellValue('G1', 'CANTIDAD EXISTENCIA')
                    ->setCellValue('H1', 'CANTIDAD REMISIONADA')
                    ->setCellValue('I1', 'CANTIDAD DISPONIBLE');

        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        $arMarcas = new \Brasa\InventarioBundle\Entity\InvMarca();
        $arMarcas = $query->getResult();

        foreach ($arMarcas as $arMarca) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arMarca->getCodigoMarcaPk())
                    ->setCellValue('B' . $i, $arMarca->getNombre())
                    ->setCellValue('C' . $i, $arMarca->getVrCostoPredeterminado())
                    ->setCellValue('D' . $i, $arMarca->getVrCostoPromedio())
                    ->setCellValue('E' . $i, $arMarca->getVrPrecioPredeterminado())
                    ->setCellValue('F' . $i, $arMarca->getPorcentajeIva())
                    ->setCellValue('G' . $i, $arMarca->getCantidadExistencia())
                    ->setCellValue('H' . $i, $arMarca->getCantidadRemisionada())
                    ->setCellValue('I' . $i, $arMarca->getCantidadDisponible());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Marca');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Marcas.xlsx"');
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