<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="eventos")
 * @ORM\Entity
 */
class Evento 
{
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
	 * @ORM\Column(name="tipo_evento", type="integer", nullable=false)
	 */
	private $tipoEvento;
	
	
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
	 * Set persona
	 *
	 * @param \AppBundle\Entity\Persona $persona
	 *
	 * @return Evento
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
	 * Get tipoId
	 *
	 * @return integer
	 */
	public function getTipoId()
	{
		return $this->tipoId;
	}
	
	/**
	 * Get tipoEvento
	 *
	 * @return integer
	 */
	public function getTipoEvento()
	{
		return $this->tipoEvento;
	}

}