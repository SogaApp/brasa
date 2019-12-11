<?php

namespace Brasa\AdministracionDocumentalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdArchivoRepository extends EntityRepository {
    public function listaDQL($codigoDocumento, $numero) {                
        $dql   = "SELECT a FROM BrasaAdministracionDocumentalBundle:AdArchivo a "
                . "WHERE a.codigoDocumentoFk = " . $codigoDocumento . " AND a.numero = " . $numero;
        return $dql;
    }                            
}