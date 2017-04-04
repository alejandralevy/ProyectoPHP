<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Persona
 *
 * @ORM\Table(name="persona", indexes={@ORM\Index(name="tipo_idx", columns={"tipo_id"}), @ORM\Index(name="rol_idx", columns={"rol_id"})})
 * @ORM\Entity
 */
class Persona
{
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
     * @ORM\Column(name="dni", type="integer", nullable=false)
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono1", type="string", length=45, nullable=false)
     */
    private $telefono1;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono2", type="string", length=45, nullable=true)
     */
    private $telefono2;

    /**
     * @var string
     *
     * @ORM\Column(name="celular1", type="string", length=45, nullable=true)
     */
    private $celular1;

    /**
     * @var string
     *
     * @ORM\Column(name="celular2", type="string", length=45, nullable=true)
     */
    private $celular2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="datetime", nullable=false)
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=45, nullable=false)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="patente", type="string", length=10, nullable=true)
     */
    private $patente;

    /**
     * @var integer
     *
     * @ORM\Column(name="habilitado", type="integer", nullable=true)
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
    private $rol;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="propietario_id", type="integer", nullable=true)
     */
    private $propietarioId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lote", type="string", length=5, nullable=true)
     */
    private $lote;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lote_autorizado", type="string", length=5, nullable=true)
     */
    private $loteAutorizado;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="interno", type="integer", nullable=true)
     */
    private $interno;

    /**
     * Set propietarioId
     *
     * @param integer $propietarioId
     *
     * @return Persona
     */
    public function setPropietarioId($propietarioId)
    {
    	$this->propietarioId = $propietarioId;
    
    	return $this;
    }
    
    /**
     * Get propietarioId
     *
     * @return integer
     */
    public function getPropietarioId()
    {
    	return $this->propietarioId;
    }
    
    /**
     * Set interno
     *
     * @param integer $interno
     *
     * @return Persona
     */
    public function setInterno($interno)
    {
    	$this->interno = $interno;
    
    	return $this;
    }
    
    /**
     * Get interno
     *
     * @return integer
     */
    public function getInterno()
    {
    	return $this->interno;
    }
    
    
    /**
     * Set lote
     *
     * @param string $lote
     *
     * @return Persona
     */
    public function setLote($lote)
    {
    	$this->lote = $lote;
    
    	return $this;
    }
    
    /**
     * Get lote
     *
     * @return string
     */
    public function getLote()
    {
    	return $this->lote;
    }
    
    
    /**
     * Set loteAutorizado
     *
     * @param string $loteAutorizado
     *
     * @return Persona
     */
    public function setLoteAutorizado($loteAutorizado)
    {
    	$this->loteAutorizado = $loteAutorizado;
    
    	return $this;
    }
    
    /**
     * Get loteAutorizado
     *
     * @return string
     */
    public function getLoteAutorizado()
    {
    	return $this->loteAutorizado;
    }

    /**
     * Set tipoId
     *
     * @param \AppBundle\Entity\Tipo $tipoId
     *
     * @return Persona
     */
    public function setTipoId(\AppBundle\Entity\Tipo $tipoId = null)
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
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Persona
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
     * @return Persona
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
     * Set telefono1
     *
     * @param string $telefono1
     *
     * @return Persona
     */
    public function setTelefono1($telefono1)
    {
        $this->telefono1 = $telefono1;

        return $this;
    }

    /**
     * Get telefono1
     *
     * @return string
     */
    public function getTelefono1()
    {
        return $this->telefono1;
    }

    /**
     * Set telefono2
     *
     * @param string $telefono2
     *
     * @return Persona
     */
    public function setTelefono2($telefono2)
    {
        $this->telefono2 = $telefono2;

        return $this;
    }

    /**
     * Get telefono2
     *
     * @return string
     */
    public function getTelefono2()
    {
        return $this->telefono2;
    }

    /**
     * Set celular1
     *
     * @param string $celular1
     *
     * @return Persona
     */
    public function setCelular1($celular1)
    {
        $this->celular1 = $celular1;

        return $this;
    }

    /**
     * Get celular1
     *
     * @return string
     */
    public function getCelular1()
    {
        return $this->celular1;
    }

    /**
     * Set celular2
     *
     * @param string $celular2
     *
     * @return Persona
     */
    public function setCelular2($celular2)
    {
        $this->celular2 = $celular2;

        return $this;
    }

    /**
     * Get celular2
     *
     * @return string
     */
    public function getCelular2()
    {
        return $this->celular2;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Persona
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Persona
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
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
     * Set patente
     *
     * @param string $patente
     *
     * @return Persona
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;

        return $this;
    }

    /**
     * Get patente
     *
     * @return string
     */
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * Set habilitado
     *
     * @param integer $habilitado
     *
     * @return Persona
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rol
     *
     * @param \AppBundle\Entity\Rol $rol
     *
     * @return Persona
     */
    public function setRol(\AppBundle\Entity\Rol $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \AppBundle\Entity\Rol
     */
    public function getRol()
    {
        return $this->rol;
    }
    
    public function getRoles()
    {
    	return array('ROLE_USER');
    }
}
