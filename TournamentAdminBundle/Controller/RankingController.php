<?php
/*
    FantasyFootball Symfony2 bundles - Symfony2 bundles collection to handle fantasy football tournament
    Copyright (C) 2017  Bertrand Madet

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
namespace FantasyFootball\TournamentAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FantasyFootball\TournamentCoreBundle\Util\DataProvider;

class RankingController extends Controller
{
  public function coachTeamAction($edition,$type)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $availableRankings = $editionObj->getRankings();
    $currentRound = $editionObj->getCurrentRound();
    if( array_key_exists($type,$availableRankings['coachTeam'] ) ){
      $params = $availableRankings['coachTeam'][$type];
      if( 'comeback' === $type ){
        $ranking = $data->getCoachTeamRankingByComeback($editionObj);
      }else if( 'td' === $type ){
        $ranking = $data->getCoachTeamRankingByTouchdown($editionObj);
      }else if( 'casualties' === $type ){
        $ranking = $data->getCoachTeamRankingByCasualties($editionObj);
      }else if( 'completions' === $type ){
        $ranking = $data->getCoachTeamRankingByCompletions($editionObj);
      }else if( 'fouls' === $type ){
        $ranking = $data->getCoachTeamRankingByFouls($editionObj);
      }else if( 'defense' === $type ){
        $ranking = $data->getCoachTeamRankingByDefense($editionObj);
      }else{
        $ranking = $data->getCoachTeamRanking($editionObj);
      }
      return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach_team.html.twig',
        ['edition' => $edition,'ranking' => $ranking,'type'=>$type,'round'=>$currentRound,
        'params' => $params,'availableRankings'=>$availableRankings]);
    }else{
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
  }

  public function coachAction($edition,$type) {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $currentRound = $editionObj->getCurrentRound();
    $availableRankings = $editionObj->getRankings();
    if( array_key_exists($type,$availableRankings['coach'] ) ){
      $params = $availableRankings['coach'][$type];
      if( 'comeback' === $type ){
        $ranking = $data->getCoachRankingByComeback($editionObj);
      }else if( 'td' === $type ){
        $ranking = $data->getCoachRankingByTouchdown($editionObj);
      }else if( 'casualties' === $type ){
        $ranking = $data->getCoachRankingByCasualties($editionObj);
      }else if( 'completions' === $type ){
        $ranking = $data->getCoachRankingByCompletions($editionObj);
      }else if( 'fouls' === $type ){
        $ranking = $data->getCoachRankingByFouls($editionObj);
      }else if( 'defense' === $type ){
        $ranking = $data->getCoachRankingByDefense($editionObj);
      }else{
        $ranking = $data->getMainCoachRanking($editionObj);
      }
      return $this->render('FantasyFootballTournamentAdminBundle:Ranking:coach.html.twig',
        ['edition' => $edition,'ranking' => $ranking,'type'=>$type,'round'=>$currentRound,
        'params' => $params,'availableRankings'=>$availableRankings]);
    }else{
      return $this->redirect($this->generateUrl('fantasy_football_tournament_admin_main'));
    }
  }

  public function allAction($edition)
  {
    $conf = $this->get('fantasy_football_core_db_conf');
    $data = new DataProvider($conf);
    $em = $this->getDoctrine()->getManager();
    $editionObj = $em->getRepository('FantasyFootballTournamentCoreBundle:Edition')
                  ->findOneById($edition);
    $availableRankings = $editionObj->getRankings();
    $allRanking = ['coach'=>[],'coachTeam'=>[]];
    $allParams = ['coach'=>[],'coachTeam'=>[]];
    foreach($availableRankings['coach'] as $type => $params){
      $allParams['coach'][$type] = $availableRankings['coach'][$type];
      if( 'comeback' === $type ){
        $allRanking['coach'][$type] = $data->getCoachRankingByComeback($editionObj);
      }else if( 'td' === $type ){
        $allRanking['coach'][$type] = $data->getCoachRankingByTouchdown($editionObj);
      }else if( 'casualties' === $type ){
        $allRanking['coach'][$type] = $data->getCoachRankingByCasualties($editionObj);
      }else if( 'completions' === $type ){
        $allRanking['coach'][$type] = $data->getCoachRankingByCompletions($editionObj);
      }else if( 'fouls' === $type ){
        $allRanking['coach'][$type] = $data->getCoachRankingByFouls($editionObj);
      }else if( 'defense' === $type ){
        $allRanking['coach'][$type] = $data->getCoachRankingByDefense($editionObj);
      }else{
        $allRanking['coach'][$type] = $data->getMainCoachRanking($editionObj);
      }
    }
    foreach($availableRankings['coachTeam'] as $type => $params){
      $allParams['coachTeam'][$type] = $availableRankings['coachTeam'][$type];
      if( 'comeback' === $type ){
        $allRanking['coachTeam'][$type] = $data->getCoachTeamRankingByComeback($editionObj);
      }else if( 'td' === $type ){
        $allRanking['coachTeam'][$type] = $data->getCoachTeamRankingByTouchdown($editionObj);
      }else if( 'casualties' === $type ){
        $allRanking['coachTeam'][$type] = $data->getCoachTeamRankingByCasualties($editionObj);
      }else if( 'completions' === $type ){
        $allRanking['coachTeam'][$type] = $data->getCoachTeamRankingByCompletions($editionObj);
      }else if( 'fouls' === $type ){
        $allRanking['coachTeam'][$type] = $data->getCoachTeamRankingByFouls($editionObj);
      }else if( 'defense' === $type ){
        $allRanking['coachTeam'][$type] = $data->getCoachTeamRankingByDefense($editionObj);
      }else{
        $allRanking['coachTeam'][$type] = $data->getCoachTeamRanking($editionObj);
      }
    }
    $currentRound = $editionObj->getCurrentRound();
    return $this->render('FantasyFootballTournamentAdminBundle:Ranking:all.html.twig',
      ['edition' => $edition,'round' => $currentRound,
      'allCoachRankings' => $allRanking['coach'],
      'allCoachTeamRankings' => $allRanking['coachTeam'],
      'allCoachParams' => $allParams['coach'],
      'allCoachTeamParams' => $allParams['coachTeam']]);
  }
}
?>
