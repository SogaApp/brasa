<?php

namespace Brasa\CarteraBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CarSaldoFavorRepository extends EntityRepository
{

    public function listaConsultaDql(){
        $em = $this->getEntityManager();
        $dql = $em->createQueryBuilder()->from("BrasaCarteraBundle:CarSaldoFavor","sf")
            ->select("sf");
        return $dql->getDQL();
    }

    public function listarSaldosClientes($codigoCliente){
        $em = $this->getEntityManager();
        $dql = $em->createQueryBuilder()->from("BrasaCarteraBundle:CarSaldoFavor","sf")
            ->select("sf")
            ->join("sf.reciboRel","r")
            ->where("r.codigoClienteFk = {$codigoCliente}")
            ->andWhere("sf.saldo > 0");

        $query = $dql->getQuery();
        return $query->getResult();
    }

}