<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Persona;
use AppBundle\Entity\Rol;
use AppBundle\Entity\Tipo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class PropietariosController extends Controller
{
	/**
	 * @Route("/propietarios", name="propietarios")
	 * @Template()
	 */
	public function propietariosAction()
	{
		 
		$user = $this->get('security.token_storage')->getToken()->getUser();
		$rol = $user->getRolId();
		$dni = $user->getDni();
		 
		$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
		$persona = $repository->findOneBy(array('dni' => $dni, 'habilitado' => '1'));
		$nombre = $persona->getNombre();
		$apellido = $persona->getApellido();
		$id = $persona->getId();
		 
		return array('rol_id' => $rol,
				'nombre' => $nombre,
				'dni' =>$dni,
				'id' => $id,
		);
	}
	
	/**
	 * @Route("/tablePropietarios", name="tableSourcePropietarios")
	 */
	public function tableSourceOperarios()
	{
		$request = Request::createFromGlobals();
	
		$cantidad_filas = $request->request->get("length");
		$comienzo = $request->request->get("start");
	
		$dni_buscado = $request->request->get("dni_buscado");
		$apellido_buscado = $request->request->get("apellido_buscado");
		$nombre_buscado = $request->request->get("nombre_buscado");
		$lote_buscado = $request->request->get("lote_buscado");
	
		$columna_orden = $request->request->get('order')['0']['column'];
		$tipo_de_orden = $request->request->get('order')['0']['dir'];
		$numero_columna = (int)$columna_orden;
	
		$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
		 
		$query = $repository->createQueryBuilder('p');
		$query->where('p.tipoId = :id AND p.habilitado = :hab');
		$query->setParameter('id', '1');
		$query->setParameter('hab', '1');
	
		if($dni_buscado != ""){
			 
			$trimmed_dni = trim($dni_buscado);
			$query->andWhere('p.dni LIKE :dni');
			$query->setParameter('dni','%'.$trimmed_dni.'%');
			 
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
		
		if($lote_buscado != ""){
		
			$trimmed_lote = strtoupper(trim($lote_buscado));
			$query->andWhere('p.lote LIKE :lote');
			$query->setParameter('lote','%'.$trimmed_lote.'%');
		
		}
	
		$nombre_columna = $this->nombreColumna($numero_columna);
		$query->orderBy($nombre_columna,$tipo_de_orden);
		 
		$query->setFirstResult($comienzo);
		$query->setMaxResults($cantidad_filas);
		 
		$propietarios = $query->getQuery()->getResult();
		$propietariosJson = array();
		foreach ($propietarios as $propietario)
		{
			$propietariosJson[] =  array(
					"id" => $propietario->getId(),
					"apellido"=> $propietario->getApellido(),
					"nombre" => $propietario->getNombre(),
					"dni" => $propietario->getDni(),
					"lote" => $propietario->getLote(),
					"telefono" => $propietario->getTelefono1(),
					"mail" => $propietario->getMail()
			);
		}
	
		return new JsonResponse($propietariosJson);
	
		 
	}
	
	/**
	* @Route("/eliminarPropietario", name="eliminarPropietario")
	*/
	public function eliminarPropietario()
	{
		 
		$request = Request::createFromGlobals();
		$id_eliminar = $request->query->get('id');
		$em = $this->getDoctrine()->getManager();
		$propietario = $em->getRepository('AppBundle:Persona')->findOneById($id_eliminar);
		 
		if (!$propietario) {
			$estado= 2;
			return new JsonResponse($estado);
		}
		else
		{
			$habilitado = 0;
			$propietario->setHabilitado($habilitado);
			
			$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
				
			$query = $repository->createQueryBuilder('p');
			
			$query->where('p.tipoId = :id AND p.propietarioId = :prop_id');
			$query->setParameter('id', '2');
			$query->setParameter('prop_id', $id_eliminar);
			$autorizados_eliminar = $query->getQuery()->getResult();
			
			foreach($autorizados_eliminar as $autorizado){
				
				$autorizado->setHabilitado('0');
			}
			
			$em->flush();
			$estado = 1;
			return new JsonResponse($estado);
		}
	
	}
	
	/**
	 * @Route("/administrarPropietario", name="administrarPropietario")
	 * @Template()
	 */
	public function administrarPropietarioAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		$rol = $user->getRolId();
		$dni = $user->getDni();
		 
		$request = Request::createFromGlobals();
		$id_editar = $request->request->get("id_editar");
		 
		$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
		$persona = $repository->findOneBy(array('dni' => $dni, 'habilitado' => '1'));
		$nombre = $persona->getNombre();
		$apellido = $persona->getApellido();
		$id = $persona->getId();
		 
		if($id_editar){
			$repositorio = $this->getDoctrine()->getRepository('AppBundle:Persona');
			$propietario = $repositorio->findOneById($id_editar);
	
		}else{
	
			$propietario = new Persona();
	
		}
		 
		return array('rol_id' => $rol,
				'nombre' => $nombre,
				'dni' =>$dni,
				'id' => $id,
				'propietario' => $propietario,
		);
		 
	}
	
	/**
	 * @Route("/validarPropietario", name="validarPropietario")
	 */
	public function validarPropietario()
	{
		$request = Request::createFromGlobals();
		$dni = $request->request->get("dni");
		$nombre = $request->request->get("nombre");
		$apellido = $request->request->get("apellido");
		$lote = $request->request->get("lote");
		$patente = $request->request->get("patente");
		$interno = $request->request->get("interno");
		$telefono1 = $request->request->get("telefono1");
		$telefono2 = $request->request->get("telefono2");
		$celular1 = $request->request->get("celular1");
		$celular2 = $request->request->get("celular2");
		$mail = $request->request->get("mail");
		$password = $request->request->get("password");
		$fecha = $request->request->get("fecha");
		$id_usuario_editado = $request->request->get("id_usuario_editado");
	
		$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
		 
		if(!$id_usuario_editado){
			//nuevo prop
			$propietario_dni = $repository->findBy(array('dni' => $dni, 'habilitado' => '1'));
			if($propietario_dni){
				$estado = 1;
				return new JsonResponse($estado);
			}else{
	
				$propietario = new Persona();
				
				$propietario->setDni($dni);
				$propietario->setApellido($apellido);
				$propietario->setNombre($nombre);
				$propietario->setLote($lote);
				$propietario->setPatente($patente);
				$propietario->setInterno($interno);
				$propietario->setTelefono1($telefono1);
				$propietario->setMail($mail);
				$propietario->setTelefono2($telefono2);
				$propietario->setCelular1($celular1);
				$propietario->setCelular2($celular2);
				 
				$datetime = \DateTime::createFromFormat('d/m/Y', $fecha);
				$propietario->setFechaNacimiento($datetime);
				$propietario->setPassword($password);
				
				$propietario->setHabilitado('1');
				
				$repositorio_rol = $this->getDoctrine()->getRepository('AppBundle:Rol');
				$rol_propietario= $repositorio_rol->findOneById(Rol::ROL_PROPIETARIO);
				$propietario->setRol($rol_propietario);
				
				$repositorio_tipo = $this->getDoctrine()->getRepository('AppBundle:Tipo');
				$tipo_propietario= $repositorio_tipo->findOneById(Tipo::TIPO_PROPIETARIO);
				$propietario->setTipoId($tipo_propietario);
	
				$em = $this->getDoctrine()->getManager();
				$em->persist($propietario);
				$em->flush();
				$estado = 2;
				return new JsonResponse($estado);
			}
	
		}else{
			//editando propietario
			$propietario_editado = $repository->findOneById($id_usuario_editado);
			$dni_propietario_editado = $propietario_editado->getDni();
			
			$existe_dni = $repository->findBy(array('dni' => $dni, 'habilitado' => '1'));
	
			if($dni_propietario_editado != $dni && ($existe_dni)){
				$estado = 1;
				return new JsonResponse($estado);
				 
			}else{
				
				$propietario_editado->setDni($dni);
				$propietario_editado->setApellido($apellido);
				$propietario_editado->setNombre($nombre);
				$propietario_editado->setLote($lote);
				$propietario_editado->setPatente($patente);
				$propietario_editado->setInterno($interno);
				$propietario_editado->setTelefono1($telefono1);
				$propietario_editado->setMail($mail);
				$propietario_editado->setTelefono2($telefono2);
				$propietario_editado->setCelular1($celular1);
				$propietario_editado->setCelular2($celular2);
					
				$datetime = \DateTime::createFromFormat('d/m/Y', $fecha);
				$propietario_editado->setFechaNacimiento($datetime);
				$propietario_editado->setPassword($password);
				 
				$em = $this->getDoctrine()->getManager();
				$em->flush();
				$estado = 3;
				return new JsonResponse($estado);
			}
		}
	}
	
	private function nombreColumna($numero_columna) {
		 
		if($numero_columna == 1)
			return 'p.apellido';
			 
			if($numero_columna == 2)
				return 'p.nombre';
				 
				if($numero_columna == 3)
					return 'p.dni';
					 
					if($numero_columna == 4)
						return 'p.lote';
					
						if($numero_columna == 5)
							return 'p.telefono';
						
							if($numero_columna == 6)
								return 'p.mail';
						 
	}
	
}