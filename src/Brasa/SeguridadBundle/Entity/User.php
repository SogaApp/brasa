<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;


/**
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=255)
     */
    private $nombreCorto;    
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $empresa; 
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $roles;    
    
    /**
     * @ORM\Column(name="tareasPendientes", type="integer")
     */
    private $tareasPendientes = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="SegRoles", inversedBy="usersRolRel")
     * @ORM\JoinColumn(name="roles", referencedColumnName="codigo_rol_pk")
     */
    protected $rolRel; 

    /**
     * @ORM\OneToMany(targetEntity="SegUsuarioPermisoEspecial", mappedBy="usuarioRel")
     */
    protected $userUsuarioPermisoEspecialRel;
    
    /**
     * @ORM\OneToMany(targetEntity="SegUsuarioRol", mappedBy="usuarioRel")
     */
    protected $usuariosRolesUsuarioRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="SegPermisoDocumento", mappedBy="usuarioRel")
     */
    protected $permisosDocumentosUsuarioRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\GeneralBundle\Entity\GenLogExtendido", mappedBy="usuarioRel")
     */
    protected $logsUsuarioRel;
    
    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->roles);
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }


    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->isActive,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->isActive,
            ) = unserialize($serialized);
    }

    public function equals(UserInterface $user) {

    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     * @return User
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Add usuariosConfiguracionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel
     * @return User
     */
    public function addUsuariosConfiguracionRel(\Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel)
    {
        $this->usuariosConfiguracionRel[] = $usuariosConfiguracionRel;

        return $this;
    }

    /**
     * Remove usuariosConfiguracionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel
     */
    public function removeUsuariosConfiguracionRel(\Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel)
    {
        $this->usuariosConfiguracionRel->removeElement($usuariosConfiguracionRel);
    }

    /**
     * Get usuariosConfiguracionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuariosConfiguracionRel()
    {
        return $this->usuariosConfiguracionRel;
    }



    /**
     * Set empresa
     *
     * @param string $empresa
     *
     * @return User
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set roles
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set rolRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegRoles $rolRel
     *
     * @return User
     */
    public function setRolRel(\Brasa\SeguridadBundle\Entity\SegRoles $rolRel = null)
    {
        $this->rolRel = $rolRel;

        return $this;
    }

    /**
     * Get rolRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegRoles
     */
    public function getRolRel()
    {
        return $this->rolRel;
    }

    /**
     * Add userUsuarioPermisoEspecialRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $userUsuarioPermisoEspecialRel
     *
     * @return User
     */
    public function addUserUsuarioPermisoEspecialRel(\Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $userUsuarioPermisoEspecialRel)
    {
        $this->userUsuarioPermisoEspecialRel[] = $userUsuarioPermisoEspecialRel;

        return $this;
    }

    /**
     * Remove userUsuarioPermisoEspecialRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $userUsuarioPermisoEspecialRel
     */
    public function removeUserUsuarioPermisoEspecialRel(\Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial $userUsuarioPermisoEspecialRel)
    {
        $this->userUsuarioPermisoEspecialRel->removeElement($userUsuarioPermisoEspecialRel);
    }

    /**
     * Get userUsuarioPermisoEspecialRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserUsuarioPermisoEspecialRel()
    {
        return $this->userUsuarioPermisoEspecialRel;
    }

    /**
     * Add usuariosRolesUsuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegUsuarioRol $usuariosRolesUsuarioRel
     *
     * @return User
     */
    public function addUsuariosRolesUsuarioRel(\Brasa\SeguridadBundle\Entity\SegUsuarioRol $usuariosRolesUsuarioRel)
    {
        $this->usuariosRolesUsuarioRel[] = $usuariosRolesUsuarioRel;

        return $this;
    }

    /**
     * Remove usuariosRolesUsuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegUsuarioRol $usuariosRolesUsuarioRel
     */
    public function removeUsuariosRolesUsuarioRel(\Brasa\SeguridadBundle\Entity\SegUsuarioRol $usuariosRolesUsuarioRel)
    {
        $this->usuariosRolesUsuarioRel->removeElement($usuariosRolesUsuarioRel);
    }

    /**
     * Get usuariosRolesUsuarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuariosRolesUsuarioRel()
    {
        return $this->usuariosRolesUsuarioRel;
    }

    /**
     * Add permisosDocumentosUsuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosUsuarioRel
     *
     * @return User
     */
    public function addPermisosDocumentosUsuarioRel(\Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosUsuarioRel)
    {
        $this->permisosDocumentosUsuarioRel[] = $permisosDocumentosUsuarioRel;

        return $this;
    }

    /**
     * Remove permisosDocumentosUsuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosUsuarioRel
     */
    public function removePermisosDocumentosUsuarioRel(\Brasa\SeguridadBundle\Entity\SegPermisoDocumento $permisosDocumentosUsuarioRel)
    {
        $this->permisosDocumentosUsuarioRel->removeElement($permisosDocumentosUsuarioRel);
    }

    /**
     * Get permisosDocumentosUsuarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermisosDocumentosUsuarioRel()
    {
        return $this->permisosDocumentosUsuarioRel;
    }

    /**
     * Set tareasPendientes
     *
     * @param integer $tareasPendientes
     *
     * @return User
     */
    public function setTareasPendientes($tareasPendientes)
    {
        $this->tareasPendientes = $tareasPendientes;

        return $this;
    }

    /**
     * Get tareasPendientes
     *
     * @return integer
     */
    public function getTareasPendientes()
    {
        return $this->tareasPendientes;
    }

    /**
     * @return mixed
     */
    public function getLogsUsuarioRel()
    {
        return $this->logsUsuarioRel;
    }

    /**
     * @param mixed $logsUsuarioRel
     */
    public function setLogsUsuarioRel($logsUsuarioRel)
    {
        $this->logsUsuarioRel = $logsUsuarioRel;
    }


    /**
     * Add logsUsuarioRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenLogExtendido $logsUsuarioRel
     *
     * @return User
     */
    public function addLogsUsuarioRel(\Brasa\GeneralBundle\Entity\GenLogExtendido $logsUsuarioRel)
    {
        $this->logsUsuarioRel[] = $logsUsuarioRel;

        return $this;
    }

    /**
     * Remove logsUsuarioRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenLogExtendido $logsUsuarioRel
     */
    public function removeLogsUsuarioRel(\Brasa\GeneralBundle\Entity\GenLogExtendido $logsUsuarioRel)
    {
        $this->logsUsuarioRel->removeElement($logsUsuarioRel);
    }
}
