<?php
namespace Brasa\AfiliacionBundle\Controller\Proceso;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoType;
class GenerarPeriodoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/proceso/generar/periodo", name="brs_afi_proceso_generar_periodo")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 104)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formulario();        
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGenerar')->isClicked()) {
                $fechaDesde = $form->get('fechaDesde')->getData();
                $fechaHasta = $form->get('fechaHasta')->getData();
                $arClientes = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                $arClientes = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findAll();
                foreach ($arClientes as $arCliente) {
                    $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                    $arPeriodo = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodo')->findOneBy(array('codigoClienteFk' =>  $arCliente->getCodigoClientePk(),'fechaDesde' => $fechaDesde, 'fechaHasta' => $fechaHasta));
                    if ($arPeriodo == null){
                        $arPeriodo = new \Brasa\AfiliacionBundle\Entity\AfiPeriodo();
                        $arPeriodo->setClienteRel($arCliente);
                        $arPeriodo->setFechaDesde($fechaDesde);
                        $arPeriodo->setFechaHasta($fechaHasta);
                        $em->persist($arPeriodo);
                    }
                }
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_proceso_generar_periodo'));
            }
        }
        return $this->render('BrasaAfiliacionBundle:Proceso/GenerarPeriodo:lista.html.twig', array(
            'form' => $form->createView()));
    }

    private function formulario() {
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar'))
            ->getForm();
        return $form;
    }
}