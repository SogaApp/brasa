<?php

/**
 * Esta clase es el Oyente de los eventos en las entidades.
 * Se puede añadir a una entidad con la anotacion "@ORM\EntityListeners({"Brasa\GeneralBundle\MisClases\EntityListener"})"
 * @author Jorge Alejandro Quiroz Serna <jakop.box@gmail.com><desarrollo5@appsoga.com>
 */

namespace Brasa\GeneralBundle\MisClases;

use Brasa\GeneralBundle\Entity\GenLogExtendido;
use Brasa\SeguridadBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Session\Session;

class EntityListener extends DefaultEntityListenerResolver
{
    const GUARDAR_DB = false;
    const ACCION_NUEVO = 'CREACION';
    const ACCION_ACTUALIZAR = 'ACTUALIZACION';
    const ACCION_ELIMINAR = 'ELIMINACION';
    const ACCION_IMPRESION = 'IMPRESION';
    const ACCION_CONSULTA = 'CONSULTA';
    private $excepciones = [
        #"/tur/proceso/generar/pedido/lista"
    ];

    /**
     * Contiene la informacion necesaria para procesar los campos de la entidad.
     * [
     *      "primaryKey" => "campoPk",
     *      "camposSeguimiento" => [
     *          'campo1',
     *          'campo2',
     *          'campoFk3' => ["CampoRel", "CampoPk"], #Nombre del campo pk en la entidad relacionada
     *          '...'
     *      ]
     * ]
     * @var array
     */
    private $infoLog = [];
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * Nombre de la accion que se esta ejecutando.
     * @var string
     */
    private $accion = null;
    /**
     * Bandera para validar si se encuentra ingresando un nuevo registro.
     * @var bool
     */
    private $esNuevo = true;
    /**
     * Bandara para validar si se encuentra eliminando un registro.
     * @var bool
     */
    private $borrando = false;
    /**
     * Nombre del campo primary key de la entidad que se procesa.
     * @var string
     */
    private $campoPrimary;
    /**
     * @var User
     */
    private $usuario;
    /**
     * Campos de la entidad a los cuales se les hara seguimiento.
     * @var array
     */
    private $camposSeguimiento = [];
    /**
     * Guarda la relacion con otras entidades.
     * @var array
     */
    private $mapeoEntidades = [];
    /**
     * Valores de cada uno de los campos a los cuales se les hara seguimiento.
     * @var array
     */
    private $valoresSeguimiento = [];
    /**
     * Json de valores de seguimiento que se mostrara en la vista.
     * @var array
     */
    private $valoresSeguimientoMostrar = [];
    /**
     * Codigo del registro padre del log, permite agrupar los logs por registros (Genera un arbol).
     * @var integer
     */
    private $codigoPadre = null;
    /**
     * Registro de ultimo cambio.
     * @var GenLogExtendido
     */
    private $ultimoCambio = null;
    /**
     * Ruta desde la cual se genero el log.
     * @var string
     */
    private $ruta;
    /**
     * Nombre de la entidad que se ha procesado.
     * @var string
     */
    private $nombreEntidad;
    /**
     * Namespace completo de la entidad que se ha procesado.
     * @var string
     */
    private $namespaceEntidad;
    /**
     * Nombre del modulo donde se proceso el registro.
     * @var string
     */
    private $modulo;
    /**
     * @var \Doctrine\ORM\EntityManager|object
     */
    private $em;
    /**
     * Nombre del campo primary key que se utiliza en la entidad.
     * @var string
     */
    private $codigoEntidadPk;

    private $procesar = true;
    private $arEntidad = null;

    public function __construct(Container $container = null)
    {
        global $kernel;
        $this->container = $kernel->getContainer();
        $this->usuario = $this->container->get("security.token_storage")->getToken()->getUser();
        $this->em = $this->container->get("doctrine.orm.entity_manager");
        $this->obtenerRuta();
    }

    /**
     * Esta funcion sirve para extraer la ruta desde la cual se proceso la entidad.
     */
    private function obtenerRuta()
    {
        $request = $this->container->get("router.request_context");
        $ruta = $request->getPathInfo();
        $scheme = $request->getScheme();
        $host = $request->getHost();
        $variables = $request->getQueryString();
        $this->ruta = "{$scheme}://{$host}{$request->getBaseUrl()}{$ruta}" . ($variables? "?{$variables}" : "");
        if(in_array($ruta, $this->excepciones)) {
            $this->procesar = false;
        }
    }

    /**
     * Esta funcion permite extraer el nombre del modulo a partir del namespace. Tambien extrae
     * el nombre de la entidad, removiendo los 3 primeros caracteres que son un prefijo del modulo.
     * @return bool
     */
    private function extraerModulo()
    {
        $this->modulo = "Modulo no definido";
        preg_match("/\\\([A-Z]{1}[A-Za-z]+(Bundle){1})\\\/", $this->namespaceEntidad, $coincidencias);
        if(!isset($coincidencias[1])) {
            return false;
        }
        $this->nombreEntidad = substr($this->namespaceEntidad, strrpos($this->namespaceEntidad, '\\') + 4);
        $bundleName = str_replace("Bundle", '', $coincidencias[1]);
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $bundleName,$palabras);
        if(!isset($palabras[1])) {
            return false;
        }
        $this->modulo = implode(' ', $palabras[1]);
        return true;
    }

    /**
     * Esta funcion se ejecuta justo antes de que doctrine haga la persistencia de los datos.
     * Se usa para definir valores iniciales para gaurdar un registro nuevo.
     * @param $entidad mixed Instancia de la entidad que se esta procesando.
     * @ORM\PrePersist()
     */
    public function prePersist($entidad)
    {
        if(!$this->procesar) { return false; }
        $this->arEntidad = $entidad;
        $this->namespaceEntidad = get_class($entidad);
        $this->extraerCampos($entidad);
        $this->extraerModulo();
        $this->esNuevo = $this->codigoEntidadPk == null;
    }

    /**
     * Esta funcion se ejecuta justo despues de que doctrine hace la persistencia de los datos.
     * Se usa para ejecutar la logica de guardado.
     * @param $entidad mixed Instancia de la entidad que se esta procesando.
     * @ORM\PostPersist()
     */
    public function postPersist($entidad)
    {
        if(!$this->procesar) { return false; }
        $validator = $this->container->get("validator");
        $errors = $validator->validate($entidad);
        if(count($errors) == 0 && method_exists($entidad, "get" . ucfirst($this->campoPrimary))) {
            $this->accion = self::ACCION_NUEVO;
            $this->codigoEntidadPk = call_user_func_array([$entidad, "get" . ucfirst($this->campoPrimary)], []);
            $this->asignarValores($entidad, $this->camposSeguimiento, $this->valoresSeguimiento);
            $this->asignarValores($entidad, $this->camposSeguimiento, $this->valoresSeguimientoMostrar, true);
            $this->guardarLog();
        } else {
            # TODO: Agregar funcionalidad para cuando haya errores en la entidad.
        }
    }

    /**
     * Esta funcion se ejecuta justo despues de que se lanza un update a un registro.
     * Se usa para capturar la informacion del registro actualizado y compararlo con la informacion anterior.
     * En caso de que no exista un log para el registro,se ingresara una nueva linea de log para el mismo.
     * @param $entidad mixed
     * @ORM\PostUpdate()
     */
    public function postUpdate($entidad)
    {
        if(!$this->procesar) { return false; }
        $this->arEntidad = $entidad;
        $this->accion = self::ACCION_ACTUALIZAR;
        $this->namespaceEntidad = get_class($entidad);
        $this->extraerModulo();
        $this->extraerCampos($entidad);
        $this->codigoEntidadPk = call_user_func_array([$entidad, "get" . ucfirst($this->campoPrimary)], []);
        $this->esNuevo = false;
        $this->ultimoCambio = $this->em->getRepository("BrasaGeneralBundle:GenLogExtendido")->getCodigoPadre($this->codigoEntidadPk);
        # Obtenemos el codigo del padre
        if($this->ultimoCambio && $this->ultimoCambio->getCodigoPadre() != "") {
            $this->codigoPadre =  $this->ultimoCambio->getCodigoPadre();
        } else if($this->ultimoCambio && $this->ultimoCambio->getCodigoPadre() == "") {
            $this->codigoPadre = $this->ultimoCambio->getCodigoLogExtendidoPk();
        }
        $this->asignarValores($entidad, $this->camposSeguimiento, $this->valoresSeguimiento);
        $this->asignarValores($entidad, $this->camposSeguimiento, $this->valoresSeguimientoMostrar, true);
        $cambios = $this->hayCambios();
        if(!$this->borrando && !$cambios) {
            return false;
        }
        $this->guardarLog();
    }

    /**
     * Esta funcion se ejecuta antes de que doctrine remueva un registro, se utiliza para capturar el id del registro
     * antes de que doctrine lo remueva de la entidad, ademas se aprovecha para inicializar algunos valores.
     * @param $entidad mixed Instancia de la entidad que esta siendo procesada.
     * @ORM\PreRemove()
     */
    public function preDelete($entidad)
    {
        if(!$this->procesar) { return false; }
        $this->accion = self::ACCION_ELIMINAR;
        $this->namespaceEntidad = get_class($entidad);
        $this->extraerModulo();
        $this->extraerCampos($entidad);
        $this->asignarValores($entidad, $this->camposSeguimiento, $this->valoresSeguimiento);
        $this->codigoEntidadPk = call_user_func_array([$entidad, "get" . ucfirst($this->campoPrimary)], []);
        $this->esNuevo = false;
        $this->borrando = true;
    }

    /**
     * Esta funcion se ejecuta despues de que doctrine remueve el registro, se utiliza para ejecutar la logica
     * que guarda la informacion que tenia el registro antes de ser eliminado.
     * Nota: Se puede capturar la entidad en este metodo tambien.
     * @ORM\PostRemove()
     */
    public function postDelete()
    {
        if(!$this->procesar) { return false; }
        $this->ultimoCambio = $this->em->getRepository("BrasaGeneralBundle:GenLogExtendido")->getCodigoPadre($this->codigoEntidadPk);
        # Obtenemos el codigo del padre
        if($this->ultimoCambio && $this->ultimoCambio->getCodigoPadre() != "") {
            $this->codigoPadre =  $this->ultimoCambio->getCodigoPadre();
        } else if($this->ultimoCambio && $this->ultimoCambio->getCodigoPadre() == "") {
            $this->codigoPadre = $this->ultimoCambio->getCodigoLogExtendidoPk();
        } else if($this->ultimoCambio) {
            $this->valoresSeguimiento = json_decode($this->ultimoCambio->getCamposSeguimiento(), true);
        }
        $this->guardarLog();
    }

    /**
     * Esta funcion permite extraer de la entidad los campos que seran procesados, ademas de
     * extraer tambien el nombre del campo primary key que usa la entidad.
     * @param $entidad
     */
    private function extraerCampos($entidad)
    {
        $this->camposSeguimiento = [];
        $this->valoresSeguimiento = [];
        $this->codigoEntidadPk = null;
        $this->codigoPadre = nulL;
        if(property_exists($entidad, 'infoLog')) {
            $campos = $entidad->infoLog['camposSeguimiento']?? [];
            foreach($campos as $clave => $campo) {
                if(!is_int($clave)) { # Si se trata de un campo con relaciones.
                    $this->camposSeguimiento[] = $clave;
                    $this->mapeoEntidades[$clave] = $campo;
                } else {
                    $this->camposSeguimiento[] = $campo;
                }
            }
            $this->campoPrimary = $entidad->infoLog['primaryKey']?? '';
        }
    }

    /**
     * Esta funcion se utiliza para extraer los valores de la entidad y almacenarlos en esta clase para
     * posteriormente procesarlos y comparar si hay cambios.
     * @param $entidad
     * @param $campos
     * @param $valores
     */
    private function asignarValores($entidad, $campos, &$valores, $conLabel = false)
    {
        foreach ($campos AS $nombreCampo) {
            if(key_exists($nombreCampo, $this->mapeoEntidades)) {
                $valores[$nombreCampo] = $this->resolverCampoRelacionado($entidad, $nombreCampo, $conLabel);
            } else if(method_exists($entidad, "get" . ucfirst($nombreCampo))) {
                $valor = call_user_func_array([$entidad, "get" . ucfirst($nombreCampo)], []);
                if($valor === false) {
                    $valor = 0;
                } else if (is_numeric($valor)) {
                    $valor = $this->convertirValorANumero($valor);
                }
                if(is_string($valor)) {
                    $valores[$nombreCampo] = str_replace('\'', '`', $valor);
                } else {
                    $valores[$nombreCampo] = $valor;
                }
            }
        }
    }

    private function convertirValorANumero($numero)
    {
        $str = strval($numero);
        if(strpos($str, '.')) {
            $resultado = floatval($str);
        } else {
            $resultado = intval($str);
        }
        return $resultado;
    }

    /**
     * Esta funcion permite validar si un campo se encuentra relacionado, usando el nombre del campo
     * de llamar al objeto relacion y extraer el valor de clave primaria.
     * @param $entidad
     * @param $nombreCampo
     * @return mixed|null
     */
    private function resolverCampoRelacionado($entidad, $nombreCampo, $conLabel = false)
    {
        $infoCampo = $this->mapeoEntidades[$nombreCampo];
        $nombreRel = $infoCampo[0]?? null;
        $pkRel = $infoCampo[1]?? null;
        $labelRel = $infoCampo[2]?? null;
        if(!$nombreRel || !$pkRel) {
            return null;
        }
        if (method_exists($entidad, "get" . ucfirst($nombreRel))) {
            $entidadRel = call_user_func_array([$entidad, "get" . ucfirst($nombreRel)], []);
            if(method_exists($entidadRel, "get" . ucfirst($pkRel))) {
                $valor = call_user_func_array([$entidadRel, "get" . ucfirst($pkRel)], []);
            }
            if($conLabel && isset($valor) && $labelRel && method_exists($entidadRel, "get" . ucfirst($labelRel))) {
                $label = call_user_func_array([$entidadRel, "get" . ucfirst($labelRel)], []);
                $valor = "({$valor}) " . $label;
            }
        }
        return $valor?? null;
    }

    /**
     * Esta funcion es generica para insert, update, delete, y es la encargada de guardar la linea de log para
     * cada registro.
     * @return bool Si no hay cambios en el registro y no se esta tratando de eliminar el mismo.
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function guardarLog()
    {
        if(self::GUARDAR_DB){
            $this->guardarEnDb();
        } else {
            $this->guardarTxt();
        }
    }

    private function guardarTxt()
    {
        $sesion = new Session();
        $clave = "cola-registro-log";
        if($sesion->has($clave)) {
            $arrLog = $sesion->get($clave);
        } else {
            $arrLog = [];
        }
        $arLog = new GenLogExtendido();
        $data = [
            'fecha' => date("Y-m-d H:i:s"),
            'codigoRegistroPk' => $this->codigoEntidadPk,
            'namespaceEntidad' => $this->namespaceEntidad,
            'camposSeguimiento' => json_encode($this->valoresSeguimiento),
            'camposSeguimientoMostrar' => json_encode($this->valoresSeguimientoMostrar),
            'ruta' => $this->ruta,
            'accion' => $this->accion,
            'codigoUsuarioFk' => $this->usuario->getId(),
            'nombreEntidad' => $this->nombreEntidad,
            'modulo' => $this->modulo,
            'codigoPadre' => $this->codigoPadre,
        ];

        $arLog->setFecha(new \DateTime(date("Y-m-d H:i:s")))
            ->setCodigoRegistroPk($this->codigoEntidadPk)
            ->setNamespaceEntidad($this->namespaceEntidad)
            ->setCamposSeguimiento(json_encode($this->valoresSeguimiento))
            ->setRuta($this->ruta)
            ->setAccion($this->accion)
            ->setUsuarioRel($this->usuario)
            ->setCodigoUsuarioFk($this->usuario->getId())
            ->setNombreEntidad($this->nombreEntidad)
            ->setModulo($this->modulo)
            ->setCodigoPadre($this->codigoPadre);

        $claveLog = $this->accion . $arLog->getCodigoRegistroPk();
        if(key_exists($claveLog, $arrLog)) {
            $arrLog[$claveLog]['obj'] = $data;
        } else {
            $arrLog[$claveLog] = [
                'obj' => $data,
                'accion' => $this->accion,
            ];
        }
        $sesion->set($clave, $arrLog);
    }

    private function guardarEnDb()
    {
        $arLog = new GenLogExtendido();
        $cambios = $this->hayCambios();
        if(!$this->borrando && !$cambios) {
            return false;
        }
        $arLog->setFecha(new \DateTime(date("Y-m-d H:i:s")))
            ->setCodigoRegistroPk($this->codigoEntidadPk)
            ->setNamespaceEntidad($this->namespaceEntidad)
            ->setCamposSeguimiento(json_encode($this->valoresSeguimiento))
            ->setRuta($this->ruta)
            ->setAccion($this->accion)
            ->setUsuarioRel($this->usuario)
            ->setCodigoUsuarioFk($this->usuario->getId())
            ->setNombreEntidad($this->nombreEntidad)
            ->setModulo($this->modulo)
            ->setCodigoPadre($this->codigoPadre);
        $this->em->persist($arLog);
        $this->em->flush($arLog);
    }

    private function hayCambios()
    {
        if($this->esNuevo) {
            return true;
        } else if($this->ultimoCambio) {
            $seguimientoAnterior = json_decode($this->ultimoCambio->getCamposSeguimiento(), true);
            $str1 = json_encode($seguimientoAnterior);
            $str2 = json_encode($this->valoresSeguimiento);
            return $str1 != $str2;
        } else {
            return true;
        }
    }
}