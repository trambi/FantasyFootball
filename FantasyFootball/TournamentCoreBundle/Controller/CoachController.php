<?php

namespace FantasyFootball\TournamentCoreBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class CoachController
{
  public function getCoachListAction($edition)
  {
    $data = new DataProvider();
    $coaches = $data->getCoachsByEdition($edition);
    return new JsonResponse($coaches);
  }
  public function getCoachAction($coachId)
  {
    $data = new DataProvider();
    $coach = $data->getCoachById($coachId);
    return new JsonResponse($coach);
  }
}

?>