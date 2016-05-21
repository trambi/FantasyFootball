<?php

namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class RankingController extends Controller
{
  public function coachTeamAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getCoachTeamRanking($editionObj);
    //print_r($ranking);
    return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_team.html.twig', 
      array('edition' => $edition,
        'ranking' => $ranking));
  }
  
  public function coachAction($edition) {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $ranking = $data->getMainCoachRanking($editionObj);
    return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach.html.twig', 
      array('edition' => $edition,
        'ranking' => $ranking));
  }

  public function coachByTouchdownAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $tdRanking = $data->getCoachRankingByTouchdown($editionObj);
    return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_touchdown.html.twig', 
      array('edition' => $edition,
        'ranking' => $tdRanking));
  }

  public function coachByCasualtiesAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $casRanking = $data->getCoachRankingByCasualties($editionObj);
    return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_casualties.html.twig', 
      array('edition' => $edition,
        'ranking' => $casRanking));
  }

  public function coachByComebackAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $comebackRanking = $data->getCoachRankingByComeback($editionObj);
    return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_comeback.html.twig', 
      array('edition' => $edition,
        'ranking' => $comebackRanking));
  }
  
  public function allAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $editionObj = $data->getEditionById($edition);
    $currentRound = $editionObj->currentRound;
    $rankings = $data->getAllRanking($editionObj);
    return $this->render('FantasyFootballTournamentAdminBundle:Ranking:all.html.twig',
      array('edition' => $edition,
        'round' => $currentRound,
        'ranking' => $rankings['ranking'],
        'casRanking' => $rankings['casRanking'],
        'tdRanking' => $rankings['tdRanking'],
        'comebackRanking' => $rankings['comebackRanking'],
        'coachTeamRanking' => $rankings['coachTeamRanking']));
  }
}
?>