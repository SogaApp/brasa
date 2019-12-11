<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_familia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoFamiliaRepository")
 */
class RhuEmpleadoFamilia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_familia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoFamiliaPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_familia_parentesco_fk", type="integer")
     */    
    private $codigoEmpleadoFamiliaParentescoFk;
    
    /**
     * @ORM\Column(name="nombres", type="string", length=150, nullable=true)
     */    
    private $nombres;
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */    
    private $codigoSexoFk;
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadSaludFk;
    
    /**
     * @ORM\Column(name="codigo_entidad_caja_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadCajaFk;
    
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */ 
    private $fechaNacimiento;
    
    /**
     * @ORM\Column(name="ocupacion", type="string", length=100, nullable=true)
     */    
    private $ocupacion;
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="empleadosFamiliasEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoFamiliaParentesco", inversedBy="empleadosFamiliasEmpleadoFamiliaParentescoRel")
     * @ORM\JoinColumn(name="codigo_empleado_familia_parentesco_fk", referencedColumnName="codigo_empleado_familia_parentesco_pk")
     */
    protected $empleadoFamiliaParentescoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadCaja", inversedBy="empleadosFamiliasEntidadCajaRel")
     * @ORM\JoinColumn(name="codigo_entidad_caja_fk", referencedColumnName="codigo_entidad_caja_pk")
     */
    protected $entidadCajaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="empleadosFamiliasEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;
    

    /**
     * Get codigoEmpleadoFamiliaPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFamiliaPk()
    {
        return $this->codigoEmpleadoFamiliaPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuEmpleadoFamilia
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set codigoEmpleadoFamiliaParentescoFk
     *
     * @param integer $codigoEmpleadoFamiliaParentescoFk
     *
     * @return RhuEmpleadoFamilia
     */
    public function setCodigoEmpleadoFamiliaParentescoFk($codigoEmpleadoFamiliaParentescoFk)
    {
        $this->codigoEmpleadoFamiliaParentescoFk = $codigoEmpleadoFamiliaParentescoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFamiliaParentescoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFamiliaParentescoFk()
    {
        return $this->codigoEmpleadoFamiliaParentescoFk;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     *
     * @return RhuEmpleadoFamilia
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return RhuEmpleadoFamilia
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk)
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * Set codigoEntidadCajaFk
     *
     * @param integer $codigoEntidadCajaFk
     *
     * @return RhuEmpleadoFamilia
     */
    public function setCodigoEntidadCajaFk($codigoEntidadCajaFk)
    {
        $this->codigoEntidadCajaFk = $codigoEntidadCajaFk;

        return $this;
    }

    /**
     * Get codigoEntidadCajaFk
     *
     * @return integer
     */
    public function getCodigoEntidadCajaFk()
    {
        return $this->codigoEntidadCajaFk;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return RhuEmpleadoFamilia
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set ocupacion
     *
     * @param string $ocupacion
     *
     * @return RhuEmpleadoFamilia
     */
    public function setOcupacion($ocupacion)
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    /**
     * Get ocupacion
     *
     * @return string
     */
    public function getOcupacion()
    {
        return $this->ocupacion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuEmpleadoFamilia
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuEmpleadoFamilia
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set empleadoFamiliaParentescoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamiliaParentesco $empleadoFamiliaParentescoRel
     *
     * @return RhuEmpleadoFamilia
     */
    public function setEmpleadoFamiliaParentescoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamiliaParentesco $empleadoFamiliaParentescoRel = null)
    {
        $this->empleadoFamiliaParentescoRel = $empleadoFamiliaParentescoRel;

        return $this;
    }

    /**
     * Get empleadoFamiliaParentescoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamiliaParentesco
     */
    public function getEmpleadoFamiliaParentescoRel()
    {
        return $this->empleadoFamiliaParentescoRel;
    }

    /**
     * Set entidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja $entidadCajaRel
     *
     * @return RhuEmpleadoFamilia
     */
    public function setEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja $entidadCajaRel = null)
    {
        $this->entidadCajaRel = $entidadCajaRel;

        return $this;
    }

    /**
     * Get entidadCajaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadCaja
     */
    public function getEntidadCajaRel()
    {
        return $this->entidadCajaRel;
    }

    /**
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return RhuEmpleadoFamilia
     */
    public function setEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel = null)
    {
        $this->entidadSaludRel = $entidadSaludRel;

        return $this;
    }

    /**
     * Get entidadSaludRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud
     */
    public function getEntidadSaludRel()
    {
        return $this->entidadSaludRel;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return RhuEmpleadoFamilia
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }
}
