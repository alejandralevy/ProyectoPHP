<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Persona;
use AppBundle\Entity\Rol;
use AppBundle\Entity\Ingreso;
use AppBundle\Entity\Tipo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IngresosController extends Controller
{
	/**
	 * @Route("/ingresos", name="ingresos")
	 * @Template()
	 */
	public function ingresosAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		$rol = $user->getRolId();
		$dni = $user->getDni();
		
		if($rol == 3){
			//rol operario
			$repository = $this->getDoctrine()->getRepository('AppBundle:Operario');
			$operario = $repository->findOneBy(array('dni' => $dni, 'habilitado' => '1'));
			$nombre = $operario->getNombre();
			$apellido = $operario->getApellido();
				
		}else{
			//rol persona o admin
			$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
			$persona = $repository->findOneBy(array('dni' => $dni, 'habilitado' => '1'));
			$nombre = $persona->getNombre();
			$apellido = $persona->getApellido();
				
		}
		
		return array('rol_id' => $rol,
				'nombre' => $nombre,
				'apellido' => $apellido,
		
		);
	}
	
	/**
	 * @Route("/buscarAutorizado", name="buscarAutorizado")
	 */
	public function buscarAutorizado()
	{
		$request = Request::createFromGlobals();
		
		$dni_buscado = $request->request->get("dni");
		$apellido_buscado = $request->request->get("apellido");
		$nombre_buscado = $request->request->get("nombre");
		$patente_buscado = $request->request->get("patente");
		
		$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
		 
		$query = $repository->createQueryBuilder('p');
		$query->where('p.tipoId = :id1 OR p.tipoId = :id2');
		$query->setParameter('id1', '2');
		$query->setParameter('id2', '4');
		$query->andWhere('p.habilitado = :hab');
		$query->setParameter('hab', '1');
		
		if($dni_buscado != ""){
		
			$trimmed_dni = trim($dni_buscado);
			$query->andWhere('p.dni LIKE :dni');
			$query->setParameter('dni','%'.$trimmed_dni.'%');
			 
		}
		 
		if($patente_buscado != ""){
		
			$trimmed_patente = strtoupper(trim($patente_buscado));
			$query->andWhere('p.patente LIKE :pat');
			$query->setParameter('pat','%'.$trimmed_patente.'%');
		}
		
		if($apellido_buscado != ""){
		
			$trimmed_apellido = strtoupper(trim($apellido_buscado));
			$query->andWhere('p.apellido LIKE :ap');
			$query->setParameter('ap','%'.$trimmed_apellido.'%');
		}
		
		if($nombre_buscado != ""){
		
			$trimmed_nombre = strtoupper(trim($nombre_buscado));
			$query->andWhere('p.nombre LIKE :nom');
			$query->setParameter('nom','%'.$trimmed_nombre.'%');
		}
		
		$personas = $query->getQuery()->getResult();
		$personasJson = array();
		foreach ($personas as $persona)
		{
			$id_propietario_autorizo = $persona->getPropietarioId();
			if($id_propietario_autorizo){
				$propietario = $repository->findOneById($id_propietario_autorizo);
				$lote = $propietario->getLote();
			}else{
				$lote = $persona->getLoteAutorizado();
			}
		
			$personasJson[] =  array(
					"id" => $persona->getId(),
					"nombre" => $persona->getNombre(),
					"apellido"=> $persona->getApellido(),
					"patente" => $persona->getPatente(),
					"dni" => $persona->getDni(),
					"lote" => $lote,
					"tipo" => $persona->getTipoId()->getId(),
			);
		}
		
		return new JsonResponse($personasJson);
	}
	
	/**
	 * @Route("/registrarIngreso", name="registrarIngreso")
	 */
	public function registarIngreso()
	{
		$request = Request::createFromGlobals();
		$id = $request->request->get("id");
		$nombre = $request->request->get("nombre");
		$apellido = $request->request->get("apellido");
		$dni = $request->request->get("dni");
		$lote = $request->request->get("lote");
		$patente = $request->request->get("patente");
		
		$repositorio_personas = $this->getDoctrine()->getRepository('AppBundle:Persona');
		$lotes = $repositorio_personas->findOneBy(array('lote' => $lote, 'habilitado' => '1'));
		
		if(!$lotes){
			$estado = 2;
			return new JsonResponse($estado);
		}else{
		
		$em = $this->getDoctrine()->getManager();
			
		$ingreso = new Ingreso();
		$repositorio_tipos = $this->getDoctrine()->getRepository('AppBundle:Tipo');
		$ingreso->setFecha(new \DateTime());
		
		if($id == -1){
			$tipo_eventual = $repositorio_tipos->findOneById(Tipo::TIPO_EVENTUAL);
			
			$eventual = new Persona();
			$eventual->setApellido($apellido);
			$eventual->setDni($dni);
			$eventual->setHabilitado('1');
			$eventual->setLoteAutorizado($lote);
			$eventual->setNombre($nombre);
			$eventual->setPatente($patente);
			$eventual->setTipoId($tipo_eventual);
			
			$em->persist($eventual);
			$em->flush();
			
			$ingreso->setTipoId($tipo_eventual);
			$ingreso->setPersona($eventual);
			
		}else if($id == -2){
			$tipo_rechazado = $repositorio_tipos->findOneById(Tipo::TIPO_RECHAZADO);
			
			$rechazado = new Persona();
			$rechazado->setApellido($apellido);
			$rechazado->setDni($dni);
			$rechazado->setHabilitado('1');
			$rechazado->setLoteAutorizado($lote);
			$rechazado->setNombre($nombre);
			$rechazado->setPatente($patente);
			$rechazado->setTipoId($tipo_rechazado);
			
			$em->persist($rechazado);
			$em->flush();
			
			$ingreso->setPersona($rechazado);
			$ingreso->setTipoId($tipo_rechazado);
			
		}else{
			
			$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
			$autorizado = $repository->findOneById($id);
			
			$ingreso->setTipoId($autorizado->getTipoId());
			$ingreso->setPersona($autorizado);
		}
		
		$em->persist($ingreso);
		$em->flush();
		$estado = 1;
		
		return new JsonResponse($estado);
		
		}
	}
}