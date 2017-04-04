<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operario
 *
 * @ORM\Table(name="operario", indexes={@ORM\Index(name="rol_idx", columns={"rol_id"})})
 * @ORM\Entity
 */
class Operario 
{
    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=45, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=45, nullable=false)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="dni", type="integer", nullable=true)
     */
    private $dni;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="datetime", nullable=false)
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=false)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="habilitado", type="integer", nullable=false)
     */
    private $habilitado;

    /**
     * @var \AppBundle\Entity\Rol
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rol")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     * })
     */
    private $rolId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
  



    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Operario
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Operario
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
     * Set dni
     *
     * @param integer $dni
     *
     * @return Operario
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
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Operario
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
     * Set password
     *
     * @param string $password
     *
     * @return Operario
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
     * Set habilitado
     *
     * @param integer $habilitado
     *
     * @return Operario
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return integer
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Set rolId
     *
     * @param \AppBundle\Entity\Rol $rol
     *
     * @return Operario
     */
    public function setRolId(\AppBundle\Entity\Rol $rol)
    {
        $this->rolId = $rol;

        return $this;
    }

    /**
     * Get rolId
     *
     * @return \AppBundle\Entity\Rol
     */
    public function getRolId()
    {
        return $this->rolId;
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
    
    public function getRoles()
    {
    	return array('ROLE_OPERARIO');
    }
}
