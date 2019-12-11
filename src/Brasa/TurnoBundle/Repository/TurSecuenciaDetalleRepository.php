<?php

namespace Brasa\TurnoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TurSecuenciaDetalleRepository extends EntityRepository {
        
    public function ListaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT sd FROM BrasaTurnoBundle:TurSecuenciaDetalle sd WHERE sd.codigoSecuenciaDetallePk <> 0";
        /*if($strNombre != "" ) {
            $dql .= " AND p.nombre LIKE '%" . $strNombre . "%'";
        }
        if($strCodigo != "" ) {
            $dql .= " AND p.codigoPlantillaPk LIKE '%" . $strCodigo . "%'";
        }*/
        //$dql .= " ORDER BY p.nombre";
        return $dql;
    }  
    
    public function convertirArray($arSecuenciaDetalle) {
        $array = array(
            '1' => $arSecuenciaDetalle->getDia1(), 
            '2' => $arSecuenciaDetalle->getDia2(), 
            '3' => $arSecuenciaDetalle->getDia3(), 
            '4' => $arSecuenciaDetalle->getDia4(), 
            '5' => $arSecuenciaDetalle->getDia5(), 
            '6' => $arSecuenciaDetalle->getDia6(), 
            '7' => $arSecuenciaDetalle->getDia7(), 
            '8' => $arSecuenciaDetalle->getDia8(), 
            '9' => $arSecuenciaDetalle->getDia9(), 
            '10' => $arSecuenciaDetalle->getDia10(), 
            '11' => $arSecuenciaDetalle->getDia11(), 
            '12' => $arSecuenciaDetalle->getDia12(), 
            '13' => $arSecuenciaDetalle->getDia13(), 
            '14' => $arSecuenciaDetalle->getDia14(), 
            '15' => $arSecuenciaDetalle->getDia15(), 
            '16' => $arSecuenciaDetalle->getDia16(), 
            '17' => $arSecuenciaDetalle->getDia17(), 
            '18' => $arSecuenciaDetalle->getDia18(), 
            '19' => $arSecuenciaDetalle->getDia19(), 
            '20' => $arSecuenciaDetalle->getDia20(), 
            '21' => $arSecuenciaDetalle->getDia21(), 
            '22' => $arSecuenciaDetalle->getDia22(), 
            '23' => $arSecuenciaDetalle->getDia23(), 
            '24' => $arSecuenciaDetalle->getDia24(), 
            '25' => $arSecuenciaDetalle->getDia25(), 
            '26' => $arSecuenciaDetalle->getDia26(), 
            '27' => $arSecuenciaDetalle->getDia27(), 
            '28' => $arSecuenciaDetalle->getDia28(), 
            '29' => $arSecuenciaDetalle->getDia29(), 
            '30' => $arSecuenciaDetalle->getDia30(), 
            '31' => $arSecuenciaDetalle->getDia31(),
            'dias' => $arSecuenciaDetalle->getDias());
        return $array;
    }
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaTurnoBundle:TurSecuenciaDetalle')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }       
    
}