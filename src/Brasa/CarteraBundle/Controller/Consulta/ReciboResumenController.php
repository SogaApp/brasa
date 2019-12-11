<?php
namespace Brasa\CarteraBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class ReciboResumenController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/cartera/consulta/recibo/resumen/", name="brs_cartera_consulta_recibo_resumen")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 54)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        //$this->estadoAnulado = 0;
        $form = $this->formularioFiltroLista();
        $form->handleRequest($request);
        $this->filtrarLista($form);        
        $this->lista();
        $fechaDesde = $form->get('fechaDesde')->getData();
        $fechaHasta = $form->get('fechaHasta')->getData();
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrarLista')->isClicked()) {
                $this->filtrarLista($form);
                //$form = $this->formularioFiltroLista();
                $this->lista();
            }
            if ($form->get('BtnImprimir')->isClicked()) {                                                
                $arConfiguracion = $em->getRepository('BrasaCarteraBundle:CarConfiguracion')->find(1);
                $codigoFormato = $arConfiguracion->getCodigoFormatoResumenRecibo();
                if($codigoFormato == 0) { //formato para cualquier empresa
                    $objImprimir = new \Brasa\CarteraBundle\Formatos\ReciboResumen();
                    $objImprimir->Generar($this, $fechaDesde->format('Y/m/d'), $fechaHasta->format('Y/m/d'));                                          
                }
                if($codigoFormato == 1) { //formato para empresa horus
                    $objImprimir = new \Brasa\CarteraBundle\Formatos\ReciboResumen1();
                    $objImprimir->Generar($this, $fechaDesde->format('Y/m/d'), $fechaHasta->format('Y/m/d'));                                          
                }
                if($codigoFormato == 2) { //formato para empresa horus 2
                    $objImprimir = new \Brasa\CarteraBundle\Formatos\ReciboResumen2();
                    $objImprimir->Generar($this, $fechaDesde->format('Y/m/d'), $fechaHasta->format('Y/m/d'));                                          
                }
            }            
        }    
        $strSql = "SELECT
            car_recibo_tipo.nombre AS tipo, 
            gen_cuenta.nombre AS cuenta, 
            COUNT(car_recibo.codigo_recibo_pk) AS numeroRecibos, 
            SUM(car_recibo.vr_total) AS vrTotalPago
            FROM car_recibo  
            LEFT JOIN car_recibo_tipo ON car_recibo.codigo_recibo_tipo_fk = car_recibo_tipo.codigo_recibo_tipo_pk 
            LEFT JOIN gen_cuenta ON car_recibo.codigo_cuenta_fk = gen_cuenta.codigo_cuenta_pk 
            WHERE car_recibo.fecha >= '" . $fechaDesde->format('Y/m/d') . "' AND car_recibo.fecha <= '" . $fechaHasta->format('Y/m/d') . "' 
            GROUP BY car_recibo.codigo_recibo_tipo_fk, car_recibo.codigo_cuenta_fk";
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $arRecibosResumen = $statement->fetchAll(); 
        
        $arRecibos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/Recibo:resumen.html.twig', array(
            'arRecibos' => $arRecibos,
            'arRecibosResumen' => $arRecibosResumen,
            'form' => $form->createView()));
    }    
            
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarRecibo')->listaConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroReciboTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }        

    private function filtrarLista ($form) {
        $session = $this->getRequest()->getSession(); 
        $arReciboTipo = $form->get('reciboTipoRel')->getData();
        if ($arReciboTipo == null){
            $codigo = "";
        } else {
            $codigo = $arReciboTipo->getCodigoReciboTipoPk();
        }
        $fechaDesde =  $form->get('fechaDesde')->getData();
        $fechaHasta =  $form->get('fechaHasta')->getData();
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());           
        $session->set('filtroReciboTipo', $codigo);
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $session->set('filtroDesde', $fechaDesde->format('Y/m/d'));
        $session->set('filtroHasta', $fechaHasta->format('Y/m/d'));
        
    }        

    private function formularioFiltroLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $arrayPropiedades = array(
                'class' => 'BrasaCarteraBundle:CarReciboTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroReciboTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarReciboTipo", $session->get('filtroReciboTipo'));
        }
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))            
            ->add('reciboTipoRel', 'entity', $arrayPropiedades)
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))            
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnFiltrarLista', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }               

}