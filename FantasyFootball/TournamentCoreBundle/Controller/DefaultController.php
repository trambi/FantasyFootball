<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
	public function indexAction()
   {
		/** @var $router \Symfony\Component\Routing\Router */
		$router = $this->container->get('router');
	   /** @var $collection \Symfony\Component\Routing\RouteCollection */
    	$collection = $router->getRouteCollection();
    	$allRoutes = $collection->all();

    	$routes = array();


		/** @var $params \Symfony\Component\Routing\Route */
    	foreach ($allRoutes as $route => $params)
    	{
			$defaults = $params->getDefaults();

			if (isset($defaults['_controller']))
			{
        		if ( 0 === strncmp($defaults['_controller'],"FantasyFootball\\TournamentCoreBundle",strlen("FantasyFootball\\TournamentCoreBundle") ) ){
        			$routes[$route]=$params->getPath();	
        		}
			}
		}
		$response = new JsonResponse($routes);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
}
