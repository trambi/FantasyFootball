<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use Symfony\Component\Debug\Exception\ContextErrorException;

class CoachController extends Controller
{
	public function getCoachListAction($edition)
	{
  	 	$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataProvider($conf);
    	$coaches = $data->getCoachsByEdition($edition);
		$response = new JsonResponse($coaches);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  	}
  	
  	public function getCoachAction($coachId)
  	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$coach = $data->getCoachById($coachId);
		$response = new JsonResponse($coach);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
}

?>