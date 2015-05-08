<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class CoachTeamController
{
  public function getCoachTeamListAction($edition)
  {
    $data = new DataProvider();
    $coachTeamArray = $data->getCoachTeamsByEdition($edition);
    $response = new JsonResponse($coachTeamArray);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getCoachTeamAction($coachTeamId)
  {
    $data = new DataProvider();
    $coachTeam = $data->getCoachTeamById($coachTeamId);
    $response = new JsonResponse($coachTeam);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
}

?>