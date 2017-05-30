<?php

namespace WEIT\EspectacularesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Ventas
 *
 * @ORM\Table("ventas")
 * @ORM\Entity(repositoryClass="WEIT\EspectacularesBundle\Entity\VentasRepository")
 */
class Ventas
{

    /**
     * @ORM\ManyToOne(targetEntity="Espectaculares", inversedBy="ventas")
     * @ORM\JoinColumn(name="espectaculares_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $espectaculares;

    /**
     * @ORM\ManyToOne(targetEntity="WEIT\ClientesBundle\Entity\Clientes", inversedBy="ventas")
     * @ORM\JoinColumn(name="clientes_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $clientes;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaInicio", type="date")
     *@Assert\NotBlank()
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaFin", type="date")
     *@Assert\NotBlank()
     */
    private $fechaFin;


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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Ventas
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Ventas
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set espectaculares
     *
     * @param \WEIT\EspectacularesBundle\Entity\Espectaculares $espectaculares
     * @return Ventas
     */
    public function setEspectaculares(\WEIT\EspectacularesBundle\Entity\Espectaculares $espectaculares = null)
    {
        $this->espectaculares = $espectaculares;

        return $this;
    }

    /**
     * Get espectaculares
     *
     * @return \WEIT\EspectacularesBundle\Entity\Espectaculares
     */
    public function getEspectaculares()
    {
        return $this->espectaculares;
    }

    /**
     * Set clientes
     *
     * @param \WEIT\ClientesBundle\Entity\Clientes $clientes
     * @return Ventas
     */
    public function setClientes(\WEIT\ClientesBundle\Entity\Clientes $clientes = null)
    {
        $this->clientes = $clientes;

        return $this;
    }

    /**
     * Get clientes
     *
     * @return \WEIT\ClientesBundle\Entity\Clientes
     */
    public function getClientes()
    {
        return $this->clientes;
    }
}
