<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class CoachTeamController extends Controller
{
  public function getCoachTeamListAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $coachTeamArray = $data->getCoachTeamsByEdition($edition);
    $response = new JsonResponse($coachTeamArray);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
  public function getCoachTeamAction($coachTeamId)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $coachTeam = $data->getCoachTeamById($coachTeamId);
    $response = new JsonResponse($coachTeam);
	$response->headers->set('Access-Control-Allow-Origin','*');
	return $response;
  }
}

?>