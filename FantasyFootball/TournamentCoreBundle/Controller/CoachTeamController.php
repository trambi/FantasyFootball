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
    return new JsonResponse($coachTeamArray);
  }
  public function getCoachTeamAction($coachTeamId)
  {
    $data = new DataProvider();
    $coachTeam = $data->getCoachTeamById($coachTeamId);
    return new JsonResponse($coachTeam);
  }
}

?>