<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Egreso
 *
 * @ORM\Table(name="egreso", indexes={@ORM\Index(name="tipo_idx", columns={"tipo_id"}), @ORM\Index(name="persona_idx", columns={"persona_id"})})
 * @ORM\Entity
 */
class Egreso
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

     /**
     * @var \AppBundle\Entity\Tipo
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tipo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     * })
     */
    private $tipoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Persona
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Persona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     * })
     */
    private $persona;



    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Egreso
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set tipoId
     *
     * @param integer $tipoId
     *
     * @return Egreso
     */
    public function setTipoId($tipoId)
    {
        $this->tipoId = $tipoId;

        return $this;
    }

    /**
     * Get tipoId
     *
     * @return integer
     */
    public function getTipoId()
    {
        return $this->tipoId;
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
     * Set persona
     *
     * @param \AppBundle\Entity\Persona $persona
     *
     * @return Egreso
     */
    public function setPersona(\AppBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;

        return $this;
    }

    /**
     * Get persona
     *
     * @return \AppBundle\Entity\Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }
}
