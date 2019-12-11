<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_permiso_documento")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegPermisoDocumentoRepository")
 */
class SegPermisoDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoDocumentoPk;
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */
    private $codigoUsuarioFk;    

    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer")
     */
    private $codigoDocumentoFk;
    
    /**
     * @ORM\Column(name="ingreso", type="boolean", nullable=false)
     */    
    private $ingreso = 0;
    
    /**
     * @ORM\Column(name="nuevo", type="boolean", nullable=false)
     */    
    private $nuevo = 0;
    
    /**
     * @ORM\Column(name="editar", type="boolean", nullable=false)
     */    
    private $editar = 0;
    
    /**
     * @ORM\Column(name="eliminar", type="boolean", nullable=false)
     */    
    private $eliminar = 0;
    
    /**
     * @ORM\Column(name="autorizar", type="boolean", nullable=false)
     */    
    private $autorizar = 0;
    
    /**
     * @ORM\Column(name="desautorizar", type="boolean", nullable=false)
     */    
    private $desautorizar = 0;
    
    /**
     * @ORM\Column(name="aprobar", type="boolean", nullable=false)
     */    
    private $aprobar = 0;
    
    /**
     * @ORM\Column(name="desaprobar", type="boolean", nullable=false)
     */    
    private $desaprobar = 0;
    
    /**
     * @ORM\Column(name="anular", type="boolean", nullable=false)
     */    
    private $anular = 0;
    
    /**
     * @ORM\Column(name="desanular", type="boolean", nullable=false)
     */    
    private $desanular = 0;
    
    /**
     * @ORM\Column(name="imprimir", type="boolean", nullable=false)
     */    
    private $imprimir = 0;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="permisosDocumentosUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="SegDocumento", inversedBy="permisosDocumentosDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;     
    

    

    /**
     * Get codigoPermisoDocumentoPk
     *
     * @return integer
     */
    public function getCodigoPermisoDocumentoPk()
    {
        return $this->codigoPermisoDocumentoPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     *
     * @return SegPermisoDocumento
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk
     *
     * @return integer
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     *
     * @return SegPermisoDocumento
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk)
    {
        $this->codigoDocumentoFk = $codigoDocumentoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoFk
     *
     * @return integer
     */
    public function getCodigoDocumentoFk()
    {
        return $this->codigoDocumentoFk;
    }

    /**
     * Set ingreso
     *
     * @param boolean $ingreso
     *
     * @return SegPermisoDocumento
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return boolean
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set nuevo
     *
     * @param boolean $nuevo
     *
     * @return SegPermisoDocumento
     */
    public function setNuevo($nuevo)
    {
        $this->nuevo = $nuevo;

        return $this;
    }

    /**
     * Get nuevo
     *
     * @return boolean
     */
    public function getNuevo()
    {
        return $this->nuevo;
    }

    /**
     * Set editar
     *
     * @param boolean $editar
     *
     * @return SegPermisoDocumento
     */
    public function setEditar($editar)
    {
        $this->editar = $editar;

        return $this;
    }

    /**
     * Get editar
     *
     * @return boolean
     */
    public function getEditar()
    {
        return $this->editar;
    }

    /**
     * Set eliminar
     *
     * @param boolean $eliminar
     *
     * @return SegPermisoDocumento
     */
    public function setEliminar($eliminar)
    {
        $this->eliminar = $eliminar;

        return $this;
    }

    /**
     * Get eliminar
     *
     * @return boolean
     */
    public function getEliminar()
    {
        return $this->eliminar;
    }

    /**
     * Set autorizar
     *
     * @param boolean $autorizar
     *
     * @return SegPermisoDocumento
     */
    public function setAutorizar($autorizar)
    {
        $this->autorizar = $autorizar;

        return $this;
    }

    /**
     * Get autorizar
     *
     * @return boolean
     */
    public function getAutorizar()
    {
        return $this->autorizar;
    }

    /**
     * Set desautorizar
     *
     * @param boolean $desautorizar
     *
     * @return SegPermisoDocumento
     */
    public function setDesautorizar($desautorizar)
    {
        $this->desautorizar = $desautorizar;

        return $this;
    }

    /**
     * Get desautorizar
     *
     * @return boolean
     */
    public function getDesautorizar()
    {
        return $this->desautorizar;
    }

    /**
     * Set aprobar
     *
     * @param boolean $aprobar
     *
     * @return SegPermisoDocumento
     */
    public function setAprobar($aprobar)
    {
        $this->aprobar = $aprobar;

        return $this;
    }

    /**
     * Get aprobar
     *
     * @return boolean
     */
    public function getAprobar()
    {
        return $this->aprobar;
    }

    /**
     * Set desaprobar
     *
     * @param boolean $desaprobar
     *
     * @return SegPermisoDocumento
     */
    public function setDesaprobar($desaprobar)
    {
        $this->desaprobar = $desaprobar;

        return $this;
    }

    /**
     * Get desaprobar
     *
     * @return boolean
     */
    public function getDesaprobar()
    {
        return $this->desaprobar;
    }

    /**
     * Set anular
     *
     * @param boolean $anular
     *
     * @return SegPermisoDocumento
     */
    public function setAnular($anular)
    {
        $this->anular = $anular;

        return $this;
    }

    /**
     * Get anular
     *
     * @return boolean
     */
    public function getAnular()
    {
        return $this->anular;
    }

    /**
     * Set desanular
     *
     * @param boolean $desanular
     *
     * @return SegPermisoDocumento
     */
    public function setDesanular($desanular)
    {
        $this->desanular = $desanular;

        return $this;
    }

    /**
     * Get desanular
     *
     * @return boolean
     */
    public function getDesanular()
    {
        return $this->desanular;
    }

    /**
     * Set imprimir
     *
     * @param boolean $imprimir
     *
     * @return SegPermisoDocumento
     */
    public function setImprimir($imprimir)
    {
        $this->imprimir = $imprimir;

        return $this;
    }

    /**
     * Get imprimir
     *
     * @return boolean
     */
    public function getImprimir()
    {
        return $this->imprimir;
    }

    /**
     * Set usuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRel
     *
     * @return SegPermisoDocumento
     */
    public function setUsuarioRel(\Brasa\SeguridadBundle\Entity\User $usuarioRel = null)
    {
        $this->usuarioRel = $usuarioRel;

        return $this;
    }

    /**
     * Get usuarioRel
     *
     * @return \Brasa\SeguridadBundle\Entity\User
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel
     *
     * @return SegPermisoDocumento
     */
    public function setDocumentoRel(\Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegDocumento
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }
}
