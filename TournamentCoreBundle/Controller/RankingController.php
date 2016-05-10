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
    $ranking = $data->getCoachTeamRanking($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }
  
  public function getCoachTeamRankingByTouchdownAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getCoachTeamRankingByTouchdown($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }
  
  public function getCoachTeamRankingByCasualtiesAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getCoachTeamRankingByCasualties($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }
  
  public function getCoachTeamRankingByComebackAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getCoachTeamRankingByComeback($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }
  
  public function getCoachTeamRankingByCompletionsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getCoachTeamRankingByCompletions($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }
  
  public function getCoachTeamRankingByFoulsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getCoachTeamRankingByFouls($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }

  public function getCoachRankingAction($edition) {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getMainCoachRanking($editionObj);
    $response = new JsonResponse($ranking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    return $response;
  }

  public function getCoachRankingByTouchdownAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $tdRanking = $data->getCoachRankingByTouchdown($editionObj);
    $response = new JsonResponse($tdRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }

  public function getCoachRankingByCasualtiesAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $casRanking = $data->getCoachRankingByCasualties($editionObj);
    $response = new JsonResponse($casRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }

  public function getCoachRankingByComebackAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $comebackRanking = $data->getCoachRankingByComeback($editionObj);
    $response = new JsonResponse($comebackRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }
  
  public function getCoachRankingByCompletionsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $completionsRanking = $data->getCoachRankingByCompletions($editionObj);
    $response = new JsonResponse($completionsRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }
  
  public function getCoachRankingByFoulsAction($edition){
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $completionsRanking = $data->getCoachRankingByFouls($editionObj);
    $response = new JsonResponse($completionsRanking);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    return $response;
  }

}

?>