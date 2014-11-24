<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class MatchController
{
  public function getPlayedMatchListAction($edition,$round)
  {
    $data = new DataProvider();
    $matchs = $data->getPlayedMatchsByEditionAndRound($edition,$round);
    return new JsonResponse($matchs);
  }
  public function getToPlayMatchListAction($edition,$round)
  {
    $data = new DataProvider();
    $matchs = $data->getToPlayMatchsByEditionAndRound($edition,$round);
    return new JsonResponse($matchs);
  }
  public function getMatchListAction($edition,$round)
  {
    $data = new DataProvider();
    $matchs = $data->getMatchsByEditionAndRound($edition,$round);
    return new JsonResponse($matchs);
  }
  public function getMatchListByCoachAction($coachId)
  {
    $data = new DataProvider();
    $matchs = $data->getMatchsByCoach($coachId);
    return new JsonResponse($matchs);
  }
  public function getMatchListByCoachTeamAction($coachTeamId)
  {
    $data = new DataProvider();
    $matchs = $data->getMatchsByCoachTeam($coachTeamId);
    return new JsonResponse($matchs);
  }
}

?>