<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;
use Symfony\Component\Debug\Exception\ContextErrorException;

class CoachController
{
  public function getCoachListAction($edition)
  {
    $data = new DataProvider();
    $coaches = $data->getCoachsByEdition($edition);
/*    
    foreach($coaches as $coach){
    	try{
    		json_encode($coach);
		}catch (ContextErrorException $e){
			if ( JSON_ERROR_UTF8 === json_last_error() ){
    			echo 'Erreur d\'encodage pour Coach ',$coach->id,"<br />\n";
    			print_r($coach);	
    		}else {
				echo 'Erreur inconnue pour Coach ',$coach->id,$e->getMessage(),"<br />\n";
			}
		}
    }*/
    return new JsonResponse($coaches);
  }
  public function getCoachAction($coachId)
  {
    $data = new DataProvider();
    $coach = $data->getCoachById($coachId);
    return new JsonResponse($coach);
  }
}

?>