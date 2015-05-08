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
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getToPlayMatchListAction($edition,$round)
  {
    $data = new DataProvider();
    $matchs = $data->getToPlayMatchsByEditionAndRound($edition,$round);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getMatchListAction($edition,$round)
  {
    $data = new DataProvider();
    $matchs = $data->getMatchsByEditionAndRound($edition,$round);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getMatchListByCoachAction($coachId)
  {
    $data = new DataProvider();
    $matchs = $data->getMatchsByCoach($coachId);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getMatchListByCoachTeamAction($coachTeamId)
  {
    $data = new DataProvider();
    $matchs = $data->getMatchsByCoachTeam($coachTeamId);
    $response = new JsonResponse($matchs);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
}

?>