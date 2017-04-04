<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Persona;
use AppBundle\Entity\Tipo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;



class AutorizadosController extends Controller
{
    /**
     * @Route("/autorizados", name="autorizados")
     * @Template() 
     */
    public function autorizadosAction()
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
     * @Route("/table", name="tableSource")
     */
    public function tableSource()
    {
    	$request = Request::createFromGlobals();
    	
    	$cantidad_filas = $request->request->get("length");
    	$comienzo = $request->request->get("start");
    	
    	$dni_buscado = $request->request->get("dni_buscado");
    	$lote_buscado = $request->request->get("lote_buscado");
    	$patente_buscado = $request->request->get("patente_buscado");
    	
    	$columna_orden = $request->request->get('order')['0']['column'];
    	$tipo_de_orden = $request->request->get('order')['0']['dir'];
    	$numero_columna = (int)$columna_orden;
    	
    	
    	$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');       	

    	$query = $repository->createQueryBuilder('p');
    	$query->where('p.tipoId = :id AND p.habilitado = :hab');
    	$query->setParameter('id', '2');
    	$query->setParameter('hab', '1');
    	
    	$user = $this->get('security.token_storage')->getToken()->getUser();    	
    	$rol = $user->getRolId();
    	
    	if($rol != 1){
    		$dni = $user->getDni();
    		$persona = $repository->findOneBy(array('dni' => $dni, 'habilitado' => '1'));

    		$id = $persona->getId();   	
    		$lote_usuario_logueado = $persona->getLote(); 
    			
    		$query->andWhere('p.propietarioId = :propId OR p.loteAutorizado = :loteAutorizado');
    		$query->setParameter('propId',$id);
    		$query->setParameter('loteAutorizado',$lote_usuario_logueado);
    	} 	
     	
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
    	
    	if($lote_buscado != ""){
    	
    		$trimmed_lote = strtoupper(trim($lote_buscado));
    		$query->leftJoin('AppBundle\Entity\Persona', 'p2', 'WITH', 'p.propietarioId = p2.id');
			$query->andWhere('p2.lote LIKE :lote OR p.loteAutorizado LIKE :lote');
    		$query->setParameter('lote','%'.$trimmed_lote.'%');
    	}
    	
    	if($numero_columna != 5){
    		
    	$nombre_columna = $this->nombreColumna($numero_columna);
    	$query->orderBy($nombre_columna,$tipo_de_orden);
    	$query->setFirstResult($comienzo);
    	$query->setMaxResults($cantidad_filas);
    	
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
            	);
    	}
    	
    	if($numero_columna == 5){
    		$todosLosRegistros = $this->ordenarArrayPorLote($personasJson, $tipo_de_orden);
    		$personasOrdenadasPorLote = array_slice($todosLosRegistros, $comienzo, $cantidad_filas);
    		
    		return new JsonResponse($personasOrdenadasPorLote);
    		
    	}
    	else{
    		return new JsonResponse($personasJson);
    	}
    }
    
    /**
     * @Route("/eliminarAutorizado", name="eliminarAutorizado")
     */
    public function eliminarAutorizado()
    {
    	$request = Request::createFromGlobals();
    	$id_eliminar = $request->query->get('id');
    	$em = $this->getDoctrine()->getManager();
    	$persona = $em->getRepository('AppBundle:Persona')->findOneById($id_eliminar);
    	
    	if (!$persona) {
    		$estado= 2;
    		return new JsonResponse($estado);
    	}
    	else
    	{
    		$habilitado = 0;
    		$persona->setHabilitado($habilitado);
    		$em->flush();
    		$estado = 1;
    		return new JsonResponse($estado);
    	}
    }
    
    /**
     * @Route("/nuevoAutorizado", name="nuevoAutorizado")
     * @Template()
     */
    
    public function nuevoAutorizadoAction()
    {
   		$user = $this->get('security.token_storage')->getToken()->getUser();
   		$rol = $user->getRolId();
    	$dni = $user->getDni();
    	
    	$request = Request::createFromGlobals();
    	$id_editar = $request->request->get("id_editar");
    	
    	$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
    	$persona = $repository->findOneByDni($dni);
    	$nombre = $persona->getNombre();
    	$apellido = $persona->getApellido();
    	$id = $persona->getId();
    	$lote = $persona->getLote();
    	
    	if($id_editar){
    		
    		$persona_enviar = $repository->findOneById($id_editar);
    		$id_propietario = $persona_enviar->getPropietarioId();
    		
    		if($id_propietario){
    			$propietario = $repository->findOneById($id_propietario);
    			$lote_persona_editar = $propietario->getLote();
    		}else{
    			$lote_persona_editar = $persona_enviar->getLoteAutorizado();
    		}
    	}else{
    		$persona_enviar = new Persona();
    		$lote_persona_editar = "";
    	}
    	return array('rol_id' => $rol,
						'nombre' => $nombre,
						'dni' =>$dni,
    					'id' => $id,
    					'lote' => $lote,
    					'persona' => $persona_enviar,
    					'lote_persona_editar' => $lote_persona_editar
			);
    }
    
    
    /**
     * @Route("/validarAutorizado", name="validarAutorizado")
     */
    public function validarAutorizado()
    {
    	$request = Request::createFromGlobals();
    	$dni = $request->request->get("dni");
    	$lote = $request->request->get("lote");
    	$nombre = $request->request->get("nombre");
    	$apellido = $request->request->get("apellido");
    	$patente = $request->request->get("patente");
    	$id_usuario_editado = $request->request->get("id_usuario_editado");
    	
    	$repository = $this->getDoctrine()->getRepository('AppBundle:Persona');
    	
    	if(!$id_usuario_editado){   //nuevo autorizado
    		 
    		$persona_dni = $repository->findBy(array('dni' => $dni, 'habilitado' => '1'));
    		$persona_lote =  $repository->findByLote($lote);
    		 
    		if($persona_dni){
    			//existe alguien con ese dni
    			$estado = 1;
    			return new JsonResponse($estado);
    		
    		}else if(!$persona_lote){
    			//no existe el lote
    			$estado = 2;
    			return new JsonResponse($estado);
    		
    		}else{
    			//no hay errores se crea el usuario
    			$user = $this->get('security.token_storage')->getToken()->getUser();
    			 
    			$rol_usuario = $user->getRolId();
    			$dni_usuario_logueado = $user->getDni();
    		
    			$autorizado = new Persona();
    			$autorizado->setNombre($nombre);
    			$autorizado->setApellido($apellido);
    			$autorizado->setDni($dni);
    			$autorizado->setPatente($patente);
    			$autorizado->setHabilitado('1');
    		
    			$repositorio_tipos = $this->getDoctrine()->getRepository('AppBundle:Tipo');
    			$tipo_atorizado = $repositorio_tipos->findOneById(Tipo::TIPO_AUTORIZADO);
    		
    			$autorizado->setTipoId($tipo_atorizado);
    		
    			if($rol_usuario == 2){
    				// el rol es propietario, el propietario que lo autoriza es el que esta logueado
    				$repositorio_personas = $this->getDoctrine()->getRepository('AppBundle:Persona');
    				$persona = $repositorio_personas->findOneBy(array('dni' => $dni_usuario_logueado, 'habilitado' => '1'));
    				$id = $persona->getId();
    				$autorizado->setPropietarioId($id);
    			}
    			 
    			if($rol_usuario == 1){
    				//es administrador, no seteo el propietario id, seteo diractamente el lote autorizado
    				$autorizado->setLoteAutorizado($lote);
    			}
    			$em = $this->getDoctrine()->getManager();
    			$em->persist($autorizado);
    			$em->flush();
    			$estado = 3;
    			return new JsonResponse($estado);
    		
    		}
    		
    		
    	}else{
    		//editando autorizado
    		$usuario_editado = $repository->findOneById($id_usuario_editado);
    		$dni_usuario_editado = $usuario_editado->getDni();
    		$persona_dni = $repository->findBy(array('dni' => $dni, 'habilitado' => '1'));
    		$persona_lote =  $repository->findByLote($lote);
    		
    		if($dni_usuario_editado != $dni && ($persona_dni)){
    				$estado = 1;
    				return new JsonResponse($estado);

    		}else if(!$persona_lote){
    				$estado = 2;
    				return new JsonResponse($estado);
    			}else{
    				//no hay problemas, actualizo el usuario
    				
    				$usuario_editado->setNombre($nombre);
    				$usuario_editado->setApellido($apellido);
    				$usuario_editado->setDni($dni);
    				$usuario_editado->setPatente($patente);
    				$user = $this->get('security.token_storage')->getToken()->getUser();
    				$rol_usuario = $user->getRolId();
    				
    				if($rol_usuario == 1){
    					$propietario_id_usuario_editado = $usuario_editado->getPropietarioId();
    					
    					if($propietario_id_usuario_editado){
    						$propietario_autorizo = $repository->findOneById($propietario_id_usuario_editado);
    						$lote_usuario_editado = $propietario_autorizo->getLote();
    					}else{
    						$lote_usuario_editado = $usuario_editado->getLoteAutorizado();
    					}
    					if($lote != $lote_usuario_editado){
    						//el admin cambio el lote
    						$usuario_editado->setPropietarioId(null);
    						$usuario_editado->setLoteAutorizado($lote);
    					}
    				}
    				
    				$em = $this->getDoctrine()->getManager();
    				$em->flush();
    				$estado = 4;
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
    	return 'p.patente';
    	
    }
    
    private function ordenarArrayPorLote($personas, $ordenamiento){
    	
    	foreach ($personas as $clave => $fila) {
    		$lote[$clave] = $fila['lote'];
    	}
    	
    	$trim_odernamiento = strtoupper(trim($ordenamiento));
    	
    	if(strcmp($trim_odernamiento, 'ASC')){
    	
    	array_multisort($lote, SORT_ASC, $personas);
    	
    	}else{
    		
    	array_multisort($lote, SORT_DESC, $personas);
    		
    	}
    	
    	
    	return $personas;
    	
    	
    }
    
    
}
