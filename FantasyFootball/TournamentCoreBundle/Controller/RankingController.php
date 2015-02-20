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
    /*$error = 0;
    try{*/
    	return new JsonResponse($ranking);
 /*   }catch(\Exception $e){
    	$error = 1;
    	
    }
    $valueInError = $ranking;
    $keyInError = 0;
    while( ( TRUE === is_array($valueInError) ) || ( TRUE === is_a($valueInError,'stdClass') ) ){
    	try{
    		$valueToInspect = $valueInError;
    		foreach($valueToInspect as $key => $value){
    			$valueInError = $value;
    			$keyInError = $key;
    			json_encode($value);
    			if( JSON_ERROR_NONE !== json_last_error() ){
    				echo "Failed json_encode for key $key\n";
    				$error = 1;
    				break;
    			}
    		}
    	}catch(\Exception $e){
    		echo "Catched error ",__LINE__,' for key : ',$keyInError,"\n";
    		$error = 1;
		}
    }
    if( 1 === $error ){
    	echo "Key : ",$keyInError," value : ",$valueInError,"\n";
    }
    return new JsonResponse(array());*/
  }
  
  
  
  public function getCoachRankingAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
    $mainRanking = $data->getMainCoachRanking($editionObj,$strategy);
    return new JsonResponse($mainRanking);
  }
  
  public function getCoachRankingByTouchdownAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
    $tdRanking = $data->getCoachRankingByTouchdown($editionObj,$strategy);
    return new JsonResponse($tdRanking);
  }
  
  public function getCoachRankingByCasualtiesAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
    $casRanking = $data->getCoachRankingByCasualties($editionObj,$strategy);
    return new JsonResponse($casRanking);
  }
  
  public function getCoachRankingByComebackAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
    $comebackRanking = $data->getCoachRankingByComeback($editionObj,$strategy);
    return new JsonResponse($comebackRanking);
  }
  
}

?>