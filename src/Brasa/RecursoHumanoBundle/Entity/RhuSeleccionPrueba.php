<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_prueba")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionPruebaRepository")
 */
class RhuSeleccionPrueba
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_prueba_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionPruebaPk;            
    
    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */    
    private $codigoSeleccionFk; 
    
    /**
     * @ORM\Column(name="codigo_seleccion_prueba_tipo_fk", type="integer")
     */    
    private $codigoSeleccionPruebaTipoFk;

    /**
     * @ORM\Column(name="resultado", type="string", length=50, nullable=true)
     */    
    private $resultado;        
    
    /**
     * @ORM\Column(name="resultado_cuantitativo", type="integer", nullable=true)
     */    
    private $resultadoCuantitativo;
    
    /**
     * @ORM\Column(name="nombre_quien_hace_prueba", type="string", length=100, nullable=true)
     */    
    private $nombreQuienHacePrueba;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;  
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesPruebasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionPruebaTipo", inversedBy="seleccionesPruebasSelecionPruebaTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_prueba_tipo_fk", referencedColumnName="codigo_seleccion_prueba_tipo_pk")
     */
    protected $seleccionPruebaTipoRel;



    

    /**
     * Get codigoSeleccionPruebaPk
     *
     * @return integer
     */
    public function getCodigoSeleccionPruebaPk()
    {
        return $this->codigoSeleccionPruebaPk;
    }

    /**
     * Set codigoSeleccionFk
     *
     * @param integer $codigoSeleccionFk
     *
     * @return RhuSeleccionPrueba
     */
    public function setCodigoSeleccionFk($codigoSeleccionFk)
    {
        $this->codigoSeleccionFk = $codigoSeleccionFk;

        return $this;
    }

    /**
     * Get codigoSeleccionFk
     *
     * @return integer
     */
    public function getCodigoSeleccionFk()
    {
        return $this->codigoSeleccionFk;
    }

    /**
     * Set codigoSeleccionPruebaTipoFk
     *
     * @param integer $codigoSeleccionPruebaTipoFk
     *
     * @return RhuSeleccionPrueba
     */
    public function setCodigoSeleccionPruebaTipoFk($codigoSeleccionPruebaTipoFk)
    {
        $this->codigoSeleccionPruebaTipoFk = $codigoSeleccionPruebaTipoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionPruebaTipoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionPruebaTipoFk()
    {
        return $this->codigoSeleccionPruebaTipoFk;
    }

    /**
     * Set resultado
     *
     * @param string $resultado
     *
     * @return RhuSeleccionPrueba
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return string
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Set resultadoCuantitativo
     *
     * @param integer $resultadoCuantitativo
     *
     * @return RhuSeleccionPrueba
     */
    public function setResultadoCuantitativo($resultadoCuantitativo)
    {
        $this->resultadoCuantitativo = $resultadoCuantitativo;

        return $this;
    }

    /**
     * Get resultadoCuantitativo
     *
     * @return integer
     */
    public function getResultadoCuantitativo()
    {
        return $this->resultadoCuantitativo;
    }

    /**
     * Set nombreQuienHacePrueba
     *
     * @param string $nombreQuienHacePrueba
     *
     * @return RhuSeleccionPrueba
     */
    public function setNombreQuienHacePrueba($nombreQuienHacePrueba)
    {
        $this->nombreQuienHacePrueba = $nombreQuienHacePrueba;

        return $this;
    }

    /**
     * Get nombreQuienHacePrueba
     *
     * @return string
     */
    public function getNombreQuienHacePrueba()
    {
        return $this->nombreQuienHacePrueba;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccionPrueba
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuSeleccionPrueba
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set seleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel
     *
     * @return RhuSeleccionPrueba
     */
    public function setSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel = null)
    {
        $this->seleccionRel = $seleccionRel;

        return $this;
    }

    /**
     * Get seleccionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion
     */
    public function getSeleccionRel()
    {
        return $this->seleccionRel;
    }

    /**
     * Set seleccionPruebaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPruebaTipo $seleccionPruebaTipoRel
     *
     * @return RhuSeleccionPrueba
     */
    public function setSeleccionPruebaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPruebaTipo $seleccionPruebaTipoRel = null)
    {
        $this->seleccionPruebaTipoRel = $seleccionPruebaTipoRel;

        return $this;
    }

    /**
     * Get seleccionPruebaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionPruebaTipo
     */
    public function getSeleccionPruebaTipoRel()
    {
        return $this->seleccionPruebaTipoRel;
    }
}
