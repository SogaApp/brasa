<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCartaType;


class CartasController extends Controller
{
    /**
     * @Route("/rhu/utilidades/cartas/generar", name="brs_rhu_utilidades_cartas_generar")
     */
    public function generarAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 82)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('cartaTipoRel', 'entity',
                array('class' => 'BrasaRecursoHumanoBundle:RhuCartaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                    ->where('ct.especial = 0')
                            ->orderBy('ct.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true,
                ))    
            ->add('fecha', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'data' => new \DateTime('now') , 'attr' => array('class' => 'date')))    
            ->add('fechaOpcional', 'date', array('required' => false,'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))    
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('BtnGenerar', 'submit', array('label'  => 'Generar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        $arCartaTipo = $form->get('cartaTipoRel')->getData();
                        $codigoCartaTipo = $arCartaTipo->getCodigoCartaTipoPk();
                        $fechaProceso = $form->get('fecha')->getData()->format('Y-m-d');
                        $fechaOpcional = $form->get('fechaOpcional')->getData();
                        $codigoContrato = $arEmpleado->getCodigoContratoActivoFk();
                        $objFormatoCarta = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCarta();
                        $objFormatoCarta->Generar($this, $codigoCartaTipo, $fechaProceso, $fechaOpcional, $codigoContrato,"","","","","","");
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Utilidades/Cartas:nuevo.html.twig', array(
            'form' => $form->createView()));
    }

}
