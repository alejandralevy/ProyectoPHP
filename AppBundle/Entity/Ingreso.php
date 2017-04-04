<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingreso
 *
 * @ORM\Table(name="ingreso", indexes={@ORM\Index(name="persona_idx", columns={"persona_id"}), @ORM\Index(name="tipo_idx", columns={"tipo_id"})})
 * @ORM\Entity
 */
class Ingreso
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
     * @var \AppBundle\Entity\Egreso
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Egreso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="egreso_id", referencedColumnName="id")
     * })
     */
    private $egreso;



    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Ingreso
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
     * @return Ingreso
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
     * @return Ingreso
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
    
    /**
     * Set egreso
     *
     * @param \AppBundle\Entity\Egreso $egreso
     *
     * @return Ingreso
     */
    public function setEgreso(\AppBundle\Entity\Egreso $egreso = null)
    {
    	$this->egreso = $egreso;
    
    	return $this;
    }
    
    /**
     * Get egreso
     *
     * @return \AppBundle\Entity\Egreso
     */
    public function getEgreso()
    {
    	return $this->egreso;
    }
}
