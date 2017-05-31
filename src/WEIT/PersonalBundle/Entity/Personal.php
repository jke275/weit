<?php

namespace WEIT\PersonalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
 use Symfony\Component\Security\Core\User\UserInterface; //Cifrar
use Symfony\Component\Validator\Constraints as Assert; //validacion
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;  //verificar datos no repetido
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Personal
 *
 * @ORM\Table("personal")
 *@UniqueEntity("username")
 * @ORM\Entity(repositoryClass="WEIT\PersonalBundle\Entity\PersonalRepository")
 */
class Personal implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     *@Assert\NotBlank()
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=200)
     *@Assert\NotBlank()
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", columnDefinition="ENUM('ROLE_ADMIN', 'ROLE_COMERCIAL')", length=50)
     *@Assert\NotBlank()
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100)
     */
    private $password;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="crear", type="boolean")
     */
    private $crear;

    /**
     * @var boolean
     *
     * @ORM\Column(name="editar", type="boolean")
     */
    private $editar;

    /**
     * @var boolean
     *
     * @ORM\Column(name="borrar", type="boolean")
     */
    private $borrar;


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
     * @return Personal
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Personal
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Personal
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Personal
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Personal
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Personal
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
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
     * Set crear
     *
     * @param boolean $crear
     * @return Personal
     */
    public function setCrear($crear)
    {
        $this->crear = $crear;

        return $this;
    }

    /**
     * Get crear
     *
     * @return boolean
     */
    public function getCrear()
    {
        return $this->crear;
    }

    /**
     * Set editar
     *
     * @param boolean $editar
     * @return Personal
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
     * Set borrar
     *
     * @param boolean $borrar
     * @return Personal
     */
    public function setBorrar($borrar)
    {
        $this->borrar = $borrar;

        return $this;
    }

    /**
     * Get borrar
     *
     * @return boolean
     */
    public function getBorrar()
    {
        return $this->borrar;
    }

     public function getRoles()
    {
        return array($this->role);
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

     public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->crear,
            $this->editar,
            $this->borrar
        ));
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->crear,
            $this->editar,
            $this->borrar
        ) = unserialize($serialized);
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
}
