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
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getToPlayMatchListAction($edition,$round)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getToPlayMatchsByEditionAndRound($edition,$round);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getMatchListAction($edition,$round)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getMatchsByEditionAndRound($edition,$round);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getMatchListByCoachAction($coachId)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getMatchsByCoach($coachId);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getMatchListByCoachTeamAction($coachTeamId)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $matchs = $data->getMatchsByCoachTeam($coachTeamId);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
}

?>