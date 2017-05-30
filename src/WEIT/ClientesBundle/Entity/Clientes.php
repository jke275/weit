<?php

namespace WEIT\ClientesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; //validacion
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Clientes
 *
 * @ORM\Table("clientes")
 * @ORM\Entity(repositoryClass="WEIT\ClientesBundle\Entity\ClientesRepository")
 */
class Clientes
{

    /**
     * @ORM\OneToMany(targetEntity="WEIT\EspectacularesBundle\Entity\Ventas", mappedBy="espectaculares")
     */
    protected $ventas;

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
     * @var integer
     *
     * @ORM\Column(name="telefono", type="integer")
     *@Assert\NotBlank()
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=100)
     *@Assert\NotBlank()
     */
    private $correo;

    public function __contruct()
    {
        $this->ventas = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Clientes
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
     * @return Clientes
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
     * Set telefono
     *
     * @param integer $telefono
     * @return Clientes
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return integer
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Clientes
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ventas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ventas
     *
     * @param \WEIT\EspectacularesBundle\Entity\Ventas $ventas
     * @return Clientes
     */
    public function addVenta(\WEIT\EspectacularesBundle\Entity\Ventas $ventas)
    {
        $this->ventas[] = $ventas;

        return $this;
    }

    /**
     * Remove ventas
     *
     * @param \WEIT\EspectacularesBundle\Entity\Ventas $ventas
     */
    public function removeVenta(\WEIT\EspectacularesBundle\Entity\Ventas $ventas)
    {
        $this->ventas->removeElement($ventas);
    }

    /**
     * Get ventas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVentas()
    {
        return $this->ventas;
    }
}
