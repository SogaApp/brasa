<?php

namespace Brasa\GeneralBundle\MisClases;


use Brasa\GeneralBundle\Entity\GenLogExtendido;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GeneralEntityListener
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $sesion = new Session();
        $em = $args->getEntityManager();
        if($sesion->has("cola-registro-log")) {
            $logPendiente = $sesion->get('cola-registro-log');
            $valores = [];
            $queries = [];
            $cont = 1;

            foreach($logPendiente AS $log) {
                $dataObj = $log['obj'];
                $dataObj['accion'] = $log['accion'];
                $valorSolo = implode(', ', array_map(function($valor) {
                    return $valor == ''? "null" : "'{$valor}'";
                }, $dataObj));
                $valores[] = "({$valorSolo})";
                if($cont >= 500) { # Se limitan los registros a 500.
                    $queries[] = $valores;
                    $valores = [];
                    $cont = 1;
                }
                $cont ++;
            }
            $queries[] = $valores;
            $columnas = implode(", ", array_map(function($campo) {
                preg_match_all('/((?:^|[A-Z])[a-z]+)/', $campo,$palabras);
                return strtolower(implode('_', $palabras[1]));
            }, array_keys($dataObj)));
            $insert = "INSERT INTO gen_log_extendido ({$columnas}) VALUES ";
            foreach ($queries as $valores) {
                $sql =  $insert . implode(', ', $valores);
                $statement = $em->getConnection()->prepare($sql);
                $statement->execute();
            }
            $sesion->remove("cola-registro-log");
        }
    }

}