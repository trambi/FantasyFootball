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
    return new JsonResponse($ranking);
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