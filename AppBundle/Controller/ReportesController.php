<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;



class ReportesController extends Controller
{
	
	/**
	 * @Route("/reportes", name="reportes")
	 * @Template()
	 */
	public function reportesAction()
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
	 * @Route("/reportesSource", name="reportesSource")
	 */
	public function reportesSource(Request $request)
	{
		 
		$cantidad_filas = $request->request->get("length");
		$comienzo = $request->request->get("start");
		
		$dni_buscado = $request->request->get("dni_buscado");
		$lote_buscado = $request->request->get("lote_buscado");
		$nombre_buscado = $request->request->get("nombre_buscado");
		$apellido_buscado = $request->request->get("apellido_buscado");
		$tipo_buscado = $request->request->get("tipo_buscado");
		$patente_buscado = $request->request->get("patente_buscado");
		$desde_buscado = $request->request->get("desde_buscado");
		$hasta_buscado = $request->request->get("hasta_buscado");
		 
		$columna_orden = $request->request->get('order')['0']['column'];
		$tipo_de_orden = $request->request->get('order')['0']['dir'];
		$numero_columna = (int)$columna_orden;
		
		$session = $request->getSession();
		$session->set('dni_buscado', $dni_buscado);
		$session->set('lote_buscado', $lote_buscado);
		$session->set('nombre_buscado', $nombre_buscado);
		$session->set('apellido_buscado', $apellido_buscado);
		$session->set('tipo_buscado', $tipo_buscado);
		$session->set('patente_buscado', $patente_buscado);
		$session->set('desde_buscado', $desde_buscado);
		$session->set('hasta_buscado', $hasta_buscado);
		$session->set('numero_columna', $numero_columna);
		$session->set('tipo_de_orden', $tipo_de_orden);
		
		$repositorio_personas = $this->getDoctrine()->getRepository('AppBundle:Persona');
		$repositorio_ingresos = $this->getDoctrine()->getRepository('AppBundle:Ingreso');
		$repositorio_egresos = $this->getDoctrine()->getRepository('AppBundle:Egreso');
		$repositorio_eventos = $this->getDoctrine()->getRepository('AppBundle:Evento');
			
			if($tipo_buscado == 1){
				$query = $repositorio_ingresos->createQueryBuilder('i');
				$eventos = $this->filtrarQuery($query, $dni_buscado, $lote_buscado, $nombre_buscado, $apellido_buscado, 
						$patente_buscado, $desde_buscado, $hasta_buscado, $tipo_de_orden, $numero_columna, $comienzo, $cantidad_filas);
				
				return new JsonResponse($eventos);
				
				}else if($tipo_buscado == 2){
					$query = $repositorio_egresos->createQueryBuilder('i');
					$eventos = $this->filtrarQuery($query, $dni_buscado, $lote_buscado, $nombre_buscado, $apellido_buscado,
						$patente_buscado, $desde_buscado, $hasta_buscado, $tipo_de_orden, $numero_columna, $comienzo, $cantidad_filas);
					
					return new JsonResponse($eventos);
					
					}else if($tipo_buscado == 3){
						$query = $repositorio_eventos->createQueryBuilder('i');
						$eventos = $this->filtrarQuery($query, $dni_buscado, $lote_buscado, $nombre_buscado, $apellido_buscado,
							$patente_buscado, $desde_buscado, $hasta_buscado, $tipo_de_orden, $numero_columna, $comienzo, $cantidad_filas);
				
				
				return new JsonResponse($eventos);
			}
			
	}
	
	/**
	 * @Route("/exportar", name="exportar")
	 */
	public function exportar(Request $request)
	{
		$repositorio_personas = $this->getDoctrine()->getRepository('AppBundle:Persona');
		$repositorio_ingresos = $this->getDoctrine()->getRepository('AppBundle:Ingreso');
		$repositorio_egresos = $this->getDoctrine()->getRepository('AppBundle:Egreso');
		$repositorio_eventos = $this->getDoctrine()->getRepository('AppBundle:Evento');
		
		$session = $request->getSession();
		$dni_buscado = $session->get('dni_buscado');
		$lote_buscado = $session->get('lote_buscado');
		$nombre_buscado = $session->get('nombre_buscado');
		$apellido_buscado = $session->get('apellido_buscado');
		$tipo_buscado = $session->get('tipo_buscado');
		$patente_buscado = $session->get('patente_buscado');
		$desde_buscado = $session->get('desde_buscado');
		$hasta_buscado = $session->get('hasta_buscado');
		$numero_columna = $session->get('numero_columna');
		$tipo_de_orden  = $session->get('tipo_de_orden');
		
		if($tipo_buscado == 1){
			$query = $repositorio_ingresos->createQueryBuilder('i');
			$eventos = $this->filtrarQuery($query, $dni_buscado, $lote_buscado, $nombre_buscado, $apellido_buscado,
					$patente_buscado, $desde_buscado, $hasta_buscado, $tipo_de_orden, $numero_columna);
		
		
		}else if($tipo_buscado == 2){
			$query = $repositorio_egresos->createQueryBuilder('i');
			$eventos = $this->filtrarQuery($query, $dni_buscado, $lote_buscado, $nombre_buscado, $apellido_buscado,
					$patente_buscado, $desde_buscado, $hasta_buscado, $tipo_de_orden, $numero_columna);
				
				
		}else if($tipo_buscado == 3){
			$query = $repositorio_eventos->createQueryBuilder('i');
			$eventos = $this->filtrarQuery($query, $dni_buscado, $lote_buscado, $nombre_buscado, $apellido_buscado,
					$patente_buscado, $desde_buscado, $hasta_buscado, $tipo_de_orden, $numero_columna);
		
		}
		
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
		
		$phpExcelObject->getProperties()
		->setCreator("Ingresos_Egresos")
		->setTitle("reportes");
		
		$phpExcelObject->setActiveSheetIndex(0);
		
		$phpExcelObject->setActiveSheetIndex(0)
		->setCellValue('A1', 'Fecha')
		->setCellValue('B1', 'Evento')
		->setCellValue('C1', 'Persona')
		->setCellValue('D1', 'Apellido')
		->setCellValue('E1', 'Nombre')
		->setCellValue('F1', 'Dni')
		->setCellValue('G1', 'Patente')
		->setCellValue('H1', 'Lote');
		
		$phpExcelObject->setActiveSheetIndex(0)
		->getColumnDimension('B')
		->setWidth(100);
		$phpExcelObject->setActiveSheetIndex(0)
		->getColumnDimension('C')
		->setWidth(100);
		$phpExcelObject->setActiveSheetIndex(0)
		->getColumnDimension('D')
		->setWidth(100);
		$phpExcelObject->setActiveSheetIndex(0)
		->getColumnDimension('E')
		->setWidth(100);
		$phpExcelObject->setActiveSheetIndex(0)
		->getColumnDimension('F')
		->setWidth(100);
		$phpExcelObject->setActiveSheetIndex(0)
		->getColumnDimension('G')
		->setWidth(100);
		$phpExcelObject->setActiveSheetIndex(0)
		->getColumnDimension('H')
		->setWidth(100);
		
		$row = 2;
		
		foreach ($eventos as $item) {
			$phpExcelObject->setActiveSheetIndex(0)
			->setCellValue('A'.$row, $item['fecha'])
			->setCellValue('B'.$row, $item['tipo'])
			->setCellValue('C'.$row, $item['tipo_persona'])
			->setCellValue('D'.$row, $item['apellido'])
			->setCellValue('E'.$row, $item['nombre'])
			->setCellValue('F'.$row, $item['dni'])
			->setCellValue('G'.$row, $item['patente'])
			->setCellValue('H'.$row, $item['lote']);
		
			$row++;
		}
		
		$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
		// se crea el response
		$response = $this->get('phpexcel')->createStreamedResponse($writer);
		// y por último se añaden las cabeceras
		$dispositionHeader = $response->headers->makeDisposition(
				ResponseHeaderBag::DISPOSITION_ATTACHMENT,
				'reportes.xls'
				);
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');
		$response->headers->set('Content-Disposition', $dispositionHeader);
		
		
		return $response;
		
		
	}
	
	private function filtrarQuery($query, $dni_buscado, $lote_buscado, $nombre_buscado, $apellido_buscado,
			$patente_buscado, $desde_buscado, $hasta_buscado, $tipo_de_orden = null, $numero_columna = null, $comienzo = null, $cantidad_filas = null){
		
				$query->join('AppBundle\Entity\Persona', 'p', 'WITH', 'i.persona = p.id');
				$user = $this->get('security.token_storage')->getToken()->getUser();
				$rol = $user->getRolId();
				
				if($rol == 2){
					$dni = $user->getDni();
					$persona = $repositorio_personas->findOneBy(array('dni' => $dni, 'habilitado' => '1'));
						
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
					
				if($nombre_buscado != ""){
						
					$trimmed_nombre = strtoupper(trim($nombre_buscado));
					$query->andWhere('p.nombre LIKE :nombre');
					$query->setParameter('nombre','%'.$trimmed_nombre.'%');
				}
					
				if($apellido_buscado != ""){
						
					$trimmed_apellido = strtoupper(trim($apellido_buscado));
					$query->andWhere('p.apellido LIKE :ape');
					$query->setParameter('ape','%'.$trimmed_apellido.'%');
				}
					
				if($desde_buscado != ""){
				
					$desde_hora = ($desde_buscado." 00:00:00");
					$datetime_desde = \DateTime::createFromFormat('d/m/Y H:i:s', $desde_hora);
					$query->andWhere('i.fecha > :desde');
					$query->setParameter('desde', $datetime_desde);
						
				}
					
				if($hasta_buscado != ""){
				
					$hasta_hora = ($hasta_buscado." 23:59:59");
					$datetime_hasta = \DateTime::createFromFormat('d/m/Y H:i:s', $hasta_hora);
					$query->andWhere('i.fecha < :hasta');
					$query->setParameter('hasta', $datetime_hasta);
				
				}
				
				if($numero_columna != 8 & $numero_columna != 3 & $numero_columna != 2){
				
					$nombre_columna = $this->nombreColumna($numero_columna);
					$query->orderBy($nombre_columna,$tipo_de_orden);
					
					if($comienzo & $cantidad_filas){
							$query->setFirstResult($comienzo);
							$query->setMaxResults($cantidad_filas);
						}
					 
					}	
					
				$eventos = $query->getQuery()->getResult();
				
				$eventosJson = array();
				$repositorio_personas = $this->getDoctrine()->getRepository('AppBundle:Persona');
				
				foreach ($eventos as $evento)
				{
					$persona_id = $evento->getPersona();
					$persona = $repositorio_personas->findOneById($persona_id);
						
					$id_propietario_autorizo = $persona->getPropietarioId();
						
					if($id_propietario_autorizo){
				
						$propietario = $repositorio_personas->findOneById($id_propietario_autorizo);
						$lote = $propietario->getLote();
					}else{
						$lote = $persona->getLoteAutorizado();
					}
					
					$tipo = (new \ReflectionClass($evento))->getShortName();
					if(strcmp($tipo, "Evento") == 0){
						$tipo_evento = $evento->getTipoEvento();
						if($tipo_evento == 1){
							$tipo = "Ingreso";
						}else{
							$tipo = "Egreso";
						}
					}
					
					$tipo_id = $persona->getTipoId();
					$tipo_persona = $tipo_id->getDescripcion();
						
					
					$eventosJson[] =  array(
							"id" => $evento->getId(),
							"fecha" => $evento->getFecha()->format('Y-m-d H:i:s'),
							"tipo"=> $tipo,
							"tipo_persona" => $tipo_persona,
							"apellido" => $persona->getApellido(),
							"nombre" => $persona->getNombre(),
							"dni" => $persona->getDni(),
							"patente" => $persona->getPatente(),
							"lote" => $lote,
					);
				}
				
				if($numero_columna == 8 || $numero_columna == 2 || $numero_columna == 3){
					$todosLosRegistros = $this->ordenarArrayPor($eventosJson, $tipo_de_orden, $numero_columna);
					
					if($comienzo & $cantidad_filas){
						$eventosOrdenados = array_slice($todosLosRegistros, $comienzo, $cantidad_filas);
						return $eventosOrdenados;
					}else{
						return $todosLosRegistros;
					}
				
				}
				else{
					return $eventosJson;
				}
	}
	
	private function nombreColumna($numero_columna) {
		 
		if($numero_columna == 1)
			return 'i.fecha';
			 
			if($numero_columna == 4)
				return 'p.apellido';
				 
				if($numero_columna == 5)
					return 'p.nombre';
					 
					if($numero_columna == 6)
						return 'p.dni';
					
						if($numero_columna == 7)
							return 'p.patente';
						 
	}
	
	
	private function ordenarArrayPor($array, $ordenamiento, $numero_columna){
		
		$criterio = $this->nombreCriterio($numero_columna);
			
		foreach ($array as $clave => $fila) {
			$columna[$clave] = $fila[$criterio];
		}
			
		$trim_odernamiento = strtoupper(trim($ordenamiento));
			
		if(strcmp($trim_odernamiento, 'ASC')){
	
			array_multisort($columna, SORT_ASC, $array);
	
		}else{
	
			array_multisort($columna, SORT_DESC, $array);
	
		}
			
		return $array;
			
	}
	
	private function nombreCriterio ($numero_columna)
	{
		if($numero_columna == 1)
			return 'fecha';
		
			if($numero_columna == 2)
				return 'tipo';
			
				if($numero_columna == 3)
					return 'tipo_persona';
		
					if($numero_columna == 4)
						return 'apellido';
					
						if($numero_columna == 5)
							return 'nombre';
		
							if($numero_columna == 6)
								return 'dni';
							
								if($numero_columna == 7)
									return 'patente';
						
									if($numero_columna == 8)
										return 'lote';
		
	}
	
}
