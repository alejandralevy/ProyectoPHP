<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;



class LoginController extends Controller
{
	
    /**
     * @Route("/login", name="_login")
     * @Template() 
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();
	
	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();
	    
	    return array(
	    		'last_username' => $lastUsername,
	            'error'         => $error,
	    );
    }
    
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
    	// this controller will not be executed,
    	// as the route is handled by the Security system
    }
    
    /**
     * @Route("/logout", name="_logout")
     */
    public function logoutAction()
    {
    	// this controller will not be executed,
    	// as the route is handled by the Security system
    }
	
	
	/**
	 * @Route("/", name="index")
	 * @Template()  
	 */
	public function indexAction()
	
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
	
	
}
