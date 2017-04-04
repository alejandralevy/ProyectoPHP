<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Operario;
use AppBundle\Entity\Rol;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class OperariosController extends Controller
{
    /**
     * @Route("/operarios", name="operarios")
     * @Template() 
     */
    public function operariosAction()
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
     * @Route("/tableOperarios", name="tableSourceOperarios")
     */
    public function tableSourceOperarios()
    {
    	$request = Request::createFromGlobals();
    	 
    	$cantidad_filas = $request->request->get("length");
    	$comienzo = $request->request->get("start");
    	 
    	$dni_buscado = $request->request->get("dni_buscado");
    	$apellido_buscado = $request->request->get("apellido_buscado");
    	$nombre_buscado = $request->request->get("nombre_buscado");
    	 
    	$columna_orden = $request->request->get('order')['0']['column'];
    	$tipo_de_orden = $request->request->get('order')['0']['dir'];
    	$numero_columna = (int)$columna_orden;
    	 
    	$repository = $this->getDoctrine()->getRepository('AppBundle:Operario');
    	
    	$query = $repository->createQueryBuilder('o');
    	$query->where('o.habilitado = :hab');
    	$query->setParameter('hab', '1');
    	 
    	if($dni_buscado != ""){
    	
    		$trimmed_dni = trim($dni_buscado);
    		$query->andWhere('o.dni LIKE :dni');
    		$query->setParameter('dni','%'.$trimmed_dni.'%');
    		 
    	}
    	 
    	if($apellido_buscado != ""){
    	
    		$trimmed_apellido = strtoupper(trim($apellido_buscado));
    		$query->andWhere('o.apellido LIKE :ap');
    		$query->setParameter('ap','%'.$trimmed_apellido.'%');
    	}
    	 
    	if($nombre_buscado != ""){
    		 
    		$trimmed_nombre = strtoupper(trim($nombre_buscado));
    		$query->andWhere('o.nombre LIKE :nom');
    		$query->setParameter('nom','%'.$trimmed_nombre.'%');
    	
    	}
    	 
    	$nombre_columna = $this->nombreColumna($numero_columna);
    	$query->orderBy($nombre_columna,$tipo_de_orden);
    	
    	$operarios_totales = $query->getQuery()->getResult();
    	$totales = count($operarios_totales);
    	
    	$query->setFirstResult($comienzo);
    	$query->setMaxResults($cantidad_filas);
    	
    	$operarios = $query->getQuery()->getResult();
    	$operariosJson = array();
    	foreach ($operarios as $operario)
    	{
    		$operariosJson[] =  array(
    				"id" => $operario->getId(),
    				"apellido"=> $operario->getApellido(),
    				"nombre" => $operario->getNombre(),
    				"dni" => $operario->getDni(),
    		);
    	}
    	 
    		//return new JsonResponse($operariosJson);
    		
    	$response = new JsonResponse();
    	$response->setData(array(
    			'operarios' => $operariosJson,
    			'recordsTotal' => $totales
    	));
    		
    	return $response;
    	 
    	
    	}
    	
   
    
    /**
     * @Route("/eliminarOperario", name="eliminarOperario")
     */
    public function eliminarOperario()
    {
    	
   		$request = Request::createFromGlobals();
    	$id_eliminar = $request->query->get('id');
    	$em = $this->getDoctrine()->getManager();
    	$operario = $em->getRepository('AppBundle:Operario')->findOneById($id_eliminar);
    	
    	if (!$operario) {
    		$estado= 2;
    		return new JsonResponse($estado);
    	}
    	else
    	{
    		$habilitado = 0;
    		$operario->setHabilitado($habilitado);
    		$em->flush();
    		$estado = 1;
    		return new JsonResponse($estado);
    	}
    
    }
    
    /**
     * @Route("/nuevoOperario", name="nuevoOperario")
     * @Template()
     */
    public function nuevoOperarioAction()
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
    		$repositorio_operarios = $this->getDoctrine()->getRepository('AppBundle:Operario');
    		$operario_enviar = $repositorio_operarios->findOneById($id_editar);
    		
    	}else{
    		
    		$operario_enviar = new Operario();
    		
    	}
    	
    	return array('rol_id' => $rol,
						'nombre' => $nombre,
						'dni' =>$dni,
    					'id' => $id,
    					'operario' => $operario_enviar,
					);
    	
    
    }
    
    /**
     * @Route("/validarOperario", name="validarOperario")
     */
    public function validarOperario()
    {
    	$request = Request::createFromGlobals();
    	$dni = $request->request->get("dni");
    	$nombre = $request->request->get("nombre");
    	$apellido = $request->request->get("apellido");
    	$password = $request->request->get("password");
    	$fecha = $request->request->get("fecha");
    	$id_usuario_editado = $request->request->get("id_usuario_editado");
    	 
    	$repository = $this->getDoctrine()->getRepository('AppBundle:Operario');
    	
    	if(!$id_usuario_editado){
    		//nuevo operario
    		$operario_dni = $repository->findBy(array('dni' => $dni, 'habilitado' => '1'));
    		if($operario_dni){
    			$estado = 1;
    			return new JsonResponse($estado);
    		}else{
    		
    			$operario = new Operario();
    			$operario->setApellido($apellido);
    			$operario->setDni($dni);
    			
    			$datetime = \DateTime::createFromFormat('d/m/Y', $fecha);
    			
    			    			
    			$operario->setFechaNacimiento($datetime);
    			$operario->setHabilitado('1');
    			$operario->setNombre($nombre);
    			$operario->setPassword($password);
    		
    			$repositorio_rol = $this->getDoctrine()->getRepository('AppBundle:Rol');
    			$rol_operario = $repositorio_rol->findOneById(Rol::ROL_OPERARIO);
    		
    			$operario->setRolId($rol_operario);
    		
    			$em = $this->getDoctrine()->getManager();
    			$em->persist($operario);
    			$em->flush();
    			$estado = 2;
    			return new JsonResponse($estado);
    		}
    		
    	}else{
    		$usuario_editado = $repository->findOneById($id_usuario_editado);
    		$dni_usuario_editado = $usuario_editado->getDni();
    		$existe_dni = $repository->findBy(array('dni' => $dni, 'habilitado' => '1'));
    		
    		if($dni_usuario_editado != $dni && ($existe_dni)){
    			$estado = 1;
    			return new JsonResponse($estado);
    			
    		}else{
    			$usuario_editado->setNombre($nombre);
    			$usuario_editado->setApellido($apellido);
    			$usuario_editado->setFechaNacimiento(new \DateTime($fecha));
    			$usuario_editado->setDni($dni);
    			$usuario_editado->setPassword($password);
    			
    			$em = $this->getDoctrine()->getManager();
    			$em->flush();
    			$estado = 3;
    			return new JsonResponse($estado);
    			
    			
    		}
    		
    		
    		
    		
    		
    	}
    	
    	
    }
    
    private function nombreColumna($numero_columna) {
    	 
    	if($numero_columna == 1)
    		return 'o.apellido';
    	 
    	if($numero_columna == 2)
    		return 'o.nombre';
    	 
    	if($numero_columna == 3)
    		return 'o.dni';
    	 
    }
    
}
