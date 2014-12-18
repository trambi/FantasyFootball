<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use FantasyFootball\TournamentCoreBundle\Util\RankingStrategyFabric;

class RankingController
{
  public function getCoachTeamRankingAction($edition)
  {
    $data = new DataProvider();
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
    $data = new DataProvider();
    $editionObj = $data->getEditionById($edition);
    $strategy = RankingStrategyFabric::getByName($editionObj->rankingStrategy); 
    $ranking = $data->getTeamRankingBetweenRounds($edition,$strategy,0,$editionObj->currentRound);
    return new JsonResponse($ranking);
  }
}

?>