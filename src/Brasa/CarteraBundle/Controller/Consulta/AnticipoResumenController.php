<?php
namespace Brasa\CarteraBundle\Controller\Consulta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
class AnticipoResumenController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/cartera/consulta/anticipo/resumen/", name="brs_cartera_consulta_anticipo_resumen")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 51)) {
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
                $codigoFormato = $arConfiguracion->getCodigoFormatoResumenAnticipo();
                if($codigoFormato == 0) { //formato para cualquier empresa
                    $objImprimir = new \Brasa\CarteraBundle\Formatos\AnticipoResumen();
                    $objImprimir->Generar($this, $fechaDesde->format('Y/m/d'), $fechaHasta->format('Y/m/d'));                                          
                }
                if($codigoFormato == 1) { //formato para empresa horus
                    $objImprimir = new \Brasa\CarteraBundle\Formatos\AnticipoResumen1();
                    $objImprimir->Generar($this, $fechaDesde->format('Y/m/d'), $fechaHasta->format('Y/m/d'));                                          
                }
                if($codigoFormato == 2) { //formato para empresa horus2
                    $objImprimir = new \Brasa\CarteraBundle\Formatos\AnticipoResumen2();
                    $objImprimir->Generar($this, $fechaDesde->format('Y/m/d'), $fechaHasta->format('Y/m/d'));                                          
                }
            }            
        }    
        $strSql = "SELECT
            
            gen_cuenta.nombre AS cuenta, 
            COUNT(car_anticipo.codigo_anticipo_pk) AS numeroAnticipos, 
            SUM(car_anticipo.vr_total) AS vrTotalPago
            FROM car_anticipo  
            
            LEFT JOIN gen_cuenta ON car_anticipo.codigo_cuenta_fk = gen_cuenta.codigo_cuenta_pk 
            WHERE car_anticipo.fecha >= '" . $fechaDesde->format('Y/m/d') . "' AND car_anticipo.fecha <= '" . $fechaHasta->format('Y/m/d') . "' 
            GROUP BY car_anticipo.codigo_cuenta_fk";
        $connection = $em->getConnection();
        $statement = $connection->prepare($strSql);        
        $statement->execute();
        $arAnticiposResumen = $statement->fetchAll(); 
        
        $arAnticipos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaCarteraBundle:Consultas/Anticipo:resumen.html.twig', array(
            'arAnticipos' => $arAnticipos,
            'arAnticiposResumen' => $arAnticiposResumen,
            'form' => $form->createView()));
    }    
            
    private function lista() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $this->strListaDql =  $em->getRepository('BrasaCarteraBundle:CarAnticipo')->listaConsultaDql(
                $session->get('filtroNumero'), 
                $session->get('filtroCodigoCliente'), 
                '',//$session->get('filtroAnticipoTipo'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta'));
    }        

    private function filtrarLista ($form) {
        $session = $this->getRequest()->getSession(); 
        /*$arAnticipoTipo = $form->get('anticipoTipoRel')->getData();
        if ($arAnticipoTipo == null){
            $codigo = "";
        } else {
            $codigo = $arAnticipoTipo->getCodigoAnticipoTipoPk();
        }*/
        $fechaDesde =  $form->get('fechaDesde')->getData();
        $fechaHasta =  $form->get('fechaHasta')->getData();
        $session->set('filtroNumero', $form->get('TxtNumero')->getData());           
        //$session->set('filtroAnticipoTipo', $codigo);
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
        /*$arrayPropiedades = array(
                'class' => 'BrasaCarteraBundle:CarAnticipoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                    ->orderBy('rt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroAnticipoTipo')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaCarteraBundle:CarAnticipoTipo", $session->get('filtroAnticipoTipo'));
        }*/
        $form = $this->createFormBuilder()
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroPedidoNumero')))            
            //->add('anticipoTipoRel', 'entity', $arrayPropiedades)
            ->add('fechaDesde', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))            
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->add('BtnFiltrarLista', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }               

}