<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class DefaultController extends Controller
{
	public function indexAction()
	{
		$router = $this->container->get('router');
    	$collection = $router->getRouteCollection();
    	$allRoutes = $collection->all();

    	$routes = array();

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
  
  public function getEditionListAction(){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editions = $data->getEditions();
		$response = new JsonResponse($editions);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  }
}
