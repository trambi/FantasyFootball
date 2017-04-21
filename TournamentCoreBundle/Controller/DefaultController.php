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
  
  public function getVersionAction(){
		$response = new JsonResponse(array('version'=>'1.15.0alpha1'));
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  }
  
  public function getEditionListAction(){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editions = $data->getEditions();
    $exposedEditions = array();
    foreach($editions as $edition){
      $exposedEditions[] = $edition->toArray();
    }
		$response = new JsonResponse($exposedEditions);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  }
  
  public function getCurrentEditionAction(){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $edition = $data->getCurrentEdition();
		$response = new JsonResponse($edition->toArray());
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  }
  
}
