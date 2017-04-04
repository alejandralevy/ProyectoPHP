<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Persona;
use AppBundle\Entity\Rol;
use AppBundle\Entity\Ingreso;
use AppBundle\Entity\Egreso;
use AppBundle\Entity\Tipo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EgresosController extends Controller
{
	/**
	 * @Route("/egresos", name="egresos")
	 * @Template()
	 */
	public function egresosAction()
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
	 * @Route("/buscarIngreso", name="buscarIngreso")
	 */
	public function buscarIngreso()
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
		$size = count($personas);
		
		if($size == 0){
			$estado = 1;
			return new JsonResponse($estado);
			
		}else if($size == 1){
			$id_persona = $personas[0]->getId();
			$repositorio_ingresos = $this->getDoctrine()->getRepository('AppBundle:Ingreso');
			$query = $repositorio_ingresos->createQueryBuilder('i');
			$query->where('i.persona = :id');
			$query->setParameter('id', $id_persona);
			$query->orderBy('i.fecha', 'DESC');
			$query->setMaxResults('1');
			
			$ingreso = $query->getQuery()->getResult();
			
			if($ingreso){
				
				$egreso = $ingreso[0]->getEgreso();
				if($egreso){
					
					$id_propietario_autorizo = $personas[0]->getPropietarioId();
					if($id_propietario_autorizo){
						$propietario = $repository->findOneById($id_propietario_autorizo);
						$lote = $propietario->getLote();
					}else{
						$lote = $personas[0]->getLoteAutorizado();
					}
					
					$personasJson[] =  array(
							'id' => $personas[0]->getId(),
							'nombre' => $personas[0]->getNombre(),
							'apellido' => $personas[0]->getApellido(),
							'dni' => $personas[0]->getDni(),
							'patente' => $personas[0]->getPatente(),
							'lote' => $lote,
							'id_ingreso' => '-1'
					);
					return new JsonResponse($personasJson);
				
				}else{
					//no tiene linkeado el egreso
					$id_propietario_autorizo = $personas[0]->getPropietarioId();
					if($id_propietario_autorizo){
						$propietario = $repository->findOneById($id_propietario_autorizo);
						$lote = $propietario->getLote();
					}else{
						$lote = $personas[0]->getLoteAutorizado();
					}
				
					$personasJson[] =  array(
							'id' => $personas[0]->getId(),
							'nombre' => $personas[0]->getNombre(),
							'apellido' => $personas[0]->getApellido(),
							'dni' => $personas[0]->getDni(),
							'patente' => $personas[0]->getPatente(),
							'lote' => $lote,
							'fecha' => date_format($ingreso[0]->getFecha(), 'Y-m-d H:i:s'),
							'id_ingreso' => $ingreso[0]->getId()
							
					);
					return new JsonResponse($personasJson);
				}
				
			}else{
				
				$id_propietario_autorizo = $personas[0]->getPropietarioId();
				if($id_propietario_autorizo){
					$propietario = $repository->findOneById($id_propietario_autorizo);
					$lote = $propietario->getLote();
				}else{
					$lote = $personas[0]->getLoteAutorizado();
				}
				
				$personasJson[] =  array(
						'id' => $personas[0]->getId(),
						'nombre' => $personas[0]->getNombre(),
						'apellido' => $personas[0]->getApellido(),
						'dni' => $personas[0]->getDni(),
						'patente' => $personas[0]->getPatente(),
						'lote' => $lote,
						'id_ingreso' => '-1'
				);
				return new JsonResponse($personasJson);
				
			}
			
		}else{
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
				);
			
			}
			return new JsonResponse($personasJson);
		}
	}
	
	/**
	 * @Route("/registrarEgreso", name="registrarEgreso")
	 */
	public function registarIngreso()
	{
		$request = Request::createFromGlobals();
		$id_persona = $request->request->get("id_persona");
		$nombre = $request->request->get("nombre");
		$apellido = $request->request->get("apellido");
		$dni = $request->request->get("dni");
		$lote = $request->request->get("lote");
		$patente = $request->request->get("patente");
		$id_ingreso = $request->request->get("id_ingreso");
	
		$repositorio_personas = $this->getDoctrine()->getRepository('AppBundle:Persona');
		$repositorio_tipos = $this->getDoctrine()->getRepository('AppBundle:Tipo');
		$repositorio_ingresos = $this->getDoctrine()->getRepository('AppBundle:Ingreso');
		$lotes = $repositorio_personas->findOneBy(array('lote' => $lote, 'habilitado' => '1'));
	
		if(!$lotes){
			$estado = 2;
			return new JsonResponse($estado);
		}else{
			$em = $this->getDoctrine()->getManager();
			$egreso = new Egreso();
			$egreso->setFecha(new \DateTime());
			
			if($id_persona == -1){
				$tipo_eventual = $repositorio_tipos->findOneById(Tipo::TIPO_EVENTUAL);
				
				$persona_eventual = new Persona();
				$persona_eventual->setApellido($apellido);
				$persona_eventual->setNombre($nombre);
				$persona_eventual->setDni($dni);
				$persona_eventual->setPatente($patente);
				$persona_eventual->setLoteAutorizado($lote);
				$persona_eventual->setHabilitado('1');
				$persona_eventual->setTipoId($tipo_eventual);
				
				$em->persist($persona_eventual);
				$em->flush();
					
				$egreso->setTipoId($tipo_eventual);
				$egreso->setPersona($persona_eventual);
				
				$em->persist($egreso);
				$em->flush();
				
				
			}else{
				
			$persona_egreso = $repositorio_personas->findOneById($id_persona);
			$egreso->setPersona($persona_egreso);
			$egreso->setTipoId($persona_egreso->getTipoId());
			
			$em->persist($egreso);
			$em->flush();
			
			}
			
			if($id_ingreso != -1){
				$ingreso = $repositorio_ingresos->findOneById($id_ingreso);
				$ingreso->setEgreso($egreso);
				$em->flush();
				
			}
			
		}
			
		$estado = 1;
		return new JsonResponse($estado);
	}
}