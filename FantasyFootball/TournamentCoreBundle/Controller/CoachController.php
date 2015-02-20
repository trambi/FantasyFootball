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
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $coach = $data->getCoachById($coachId);
    return new JsonResponse($coach);
  }
}

?>