<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class RankingController extends Controller
{
  public function getCoachTeamRankingAction($edition)
  {
  		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$editionObj = $data->getEditionById($edition);
		$strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
		$ranking = $data->getCoachTeamRanking($edition,$strategy);
    	$response = new JsonResponse($ranking);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
  
	public function getCoachRankingAction($edition)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$editionObj = $data->getEditionById($edition);
		$strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
		$ranking = $data->getMainCoachRanking($editionObj,$strategy);
		$response = new JsonResponse($ranking);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
	}
  
	public function getCoachRankingByTouchdownAction($edition)
	{
		$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$editionObj = $data->getEditionById($edition);
		$strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
		$tdRanking = $data->getCoachRankingByTouchdown($editionObj,$strategy);
		$response = new JsonResponse($tdRanking);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  	}
  
  	public function getCoachRankingByCasualtiesAction($edition)
  	{
    	$conf = $this->get('fantasy_football_core_db_conf');
		$data = new DataProvider($conf);
		$editionObj = $data->getEditionById($edition);
		$strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
		$casRanking = $data->getCoachRankingByCasualties($editionObj,$strategy);
    	$response = new JsonResponse($casRanking);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  	}
  
  	public function getCoachRankingByComebackAction($edition)
  	{
    	$conf = $this->get('fantasy_football_core_db_conf');
    	$data = new DataProvider($conf);
    	$editionObj = $data->getEditionById($edition);
    	$strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
    	$comebackRanking = $data->getCoachRankingByComeback($editionObj,$strategy);
    	$response = new JsonResponse($comebackRanking);
		$response->headers->set('Access-Control-Allow-Origin','*');
		return $response;
  	}
  
}

?>