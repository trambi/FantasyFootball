<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class MatchController extends Controller
{
  public function getPlayedMatchListAction($edition,$round)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getPlayedMatchsByEditionAndRound($edition,$round);
    return new JsonResponse($matchs);
  }
  public function getToPlayMatchListAction($edition,$round)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getToPlayMatchsByEditionAndRound($edition,$round);
    return new JsonResponse($matchs);
  }
  public function getMatchListAction($edition,$round)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getMatchsByEditionAndRound($edition,$round);
    return new JsonResponse($matchs);
  }
  public function getMatchListByCoachAction($coachId)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getMatchsByCoach($coachId);
    return new JsonResponse($matchs);
  }
  public function getMatchListByCoachTeamAction($coachTeamId)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getMatchsByCoachTeam($coachTeamId);
    return new JsonResponse($matchs);
  }
}

?>