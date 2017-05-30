<?php

namespace WEIT\EspectacularesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Espectaculares
 *
 * @ORM\Table("espectaculares")
 * @ORM\Entity(repositoryClass="WEIT\EspectacularesBundle\Entity\EspectacularesRepository")
 */
class Espectaculares
{

    /**
     * @ORM\OneToMany(targetEntity="Ventas", mappedBy="espectaculares")
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
     * @ORM\Column(name="calle", type="string", length=255)
     *@Assert\NotBlank()
     */
    private $calle;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     *@Assert\NotBlank()
     */
    private $numero;

    /**
     * @var integer
     *
     * @ORM\Column(name="largo", type="integer")
     *@Assert\NotBlank()
     */
    private $largo;

    /**
     * @var integer
     *
     * @ORM\Column(name="ancho", type="integer")
     *@Assert\NotBlank()
     */
    private $ancho;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", columnDefinition="ENUM('LIBRE', 'BLOQUEADO', 'VENDIDO')", length=50)
     *@Assert\NotBlank()
     */
    private $estado;

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
     * Set calle
     *
     * @param string $calle
     * @return Espectaculares
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;

        return $this;
    }

    /**
     * Get calle
     *
     * @return string
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Espectaculares
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set largo
     *
     * @param integer $largo
     * @return Espectaculares
     */
    public function setLargo($largo)
    {
        $this->largo = $largo;

        return $this;
    }

    /**
     * Get largo
     *
     * @return integer
     */
    public function getLargo()
    {
        return $this->largo;
    }

    /**
     * Set ancho
     *
     * @param integer $ancho
     * @return Espectaculares
     */
    public function setAncho($ancho)
    {
        $this->ancho = $ancho;

        return $this;
    }

    /**
     * Get ancho
     *
     * @return integer
     */
    public function getAncho()
    {
        return $this->ancho;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Espectaculares
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
     * Set estado
     *
     * @param string $estado
     * @return Espectaculares
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
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
     * @return Espectaculares
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
