<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="usuarios")
 * @ORM\Entity
 */
class Usuario implements UserInterface, \Serializable
//agregar extends serializable
{
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="dni", type="integer", nullable=false, unique=true)
	 * @ORM\Id
	 */
	private $dni;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="password", type="string", length=45, nullable=true)
	 */
	private $password;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="rol_id", type="integer", nullable=false)
	 * @ORM\Id
	 */
	private $rolId;
	
	/**
	 * Set dni
	 *
	 * @param integer $dni
	 *
	 * @return Persona
	 */
	public function setDni($dni)
	{
		$this->dni = $dni;
	
		return $this;
	}
	
	/**
	 * Get dni
	 *
	 * @return integer
	 */
	public function getDni()
	{
		return $this->dni;
	}
	
	/**
	 * Set password
	 *
	 * @param string $password
	 *
	 * @return Persona
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
	 * Set rolId
	 *
	 * @param integer $rolId
	 *
	 * @return Persona
	 */
	public function setRolId($rolId)
	{
		$this->rolId = $rolId;
	
		return $this;
	}
	
	/**
	 * Get rolId
	 *
	 * @return integer
	 */
	public function getRolId()
	{
		return $this->rolId;
	}
	
	/************* SECURITY *************/
	
	//metodos redefinidos de UserInterface
	public function getUsername()
	{
		return $this->dni;
	}
	 
	public function getSalt()
	{
		// you *may* need a real salt depending on your encoder
		// see section on salt below
		return null;
	}
	 
	public function getRoles()
	{
		return array();
	}
	 
	public function eraseCredentials()
	{
	}
	 
	/** @see \Serializable::serialize() */
	public function serialize()
	{
		return serialize(array(
				$this->dni,
				$this->password,
				$this->rolId
				// see section on salt below
				// $this->salt,
		));
	}
	 
	/** @see \Serializable::unserialize() */
	public function unserialize($serialized)
	{
		list (
				$this->dni,
				$this->password,
				$this->rolId
				// see section on salt below
				// $this->salt
		) = unserialize($serialized);
	}
	
	
}