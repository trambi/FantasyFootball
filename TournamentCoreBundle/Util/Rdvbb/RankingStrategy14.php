<?php

namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategy14 implements IRankingStrategy {
  const POINT_MULTIPLIER = 500;

  public function useCoachTeamPoints() {
    return true;
  }

  public function computePoints(&$points1, &$points2, $td1, $td2, $cas1, $cas2) {
    $points = PointsComputor::win2Draw1Loss0($td1,$td2,$cas1,$cas2);
    $points1 = $points['points1'] * self::POINT_MULTIPLIER;
    $points2 = $points['points2'] * self::POINT_MULTIPLIER;
  }
    
  public function compareCoachs($coach1, $coach2) {
    $returnValue = 0;
    $params = array('points','win','draw','opponentsPoints');
    foreach ($params as $param){
      $returnValue = $coach2->$param - $coach1->$param ;
      if( 0 ==! $returnValue ){
        break;
      }
    }
    if ( 0 === $returnValue){
      $returnValue = ($coach2->netTd + $coach2->netCasualties ) - ($coach1->netTd + $coach1->netCasualties) ;
    }
    return $returnValue;
  }

  public function compareCoachTeams($item1, $item2) {
    $returnValue = 1;
    $special1 = 0;
    $special2 = 0;
    if(isset($item1->special)){
      $special1 = $item1->special;
    }
    if(isset($item2->special)){
      $special2 = $item2->special;
    }
    $returnValue = $special2 - $special1;
    if(0 !== $returnValue){
      return $returnValue;
    }
    $params = array('coachTeamPoints','win','draw','opponentCoachTeamPoints','opponentsPoints');
    foreach ($params as $param){
      $returnValue = $item2->$param - $item1->$param ;
      if( 0 ==! $returnValue ){
        break;
      }
    }
    return $returnValue;
  }

  public function computeCoachTeamPoints(&$points1, &$points2, $td1Array, $td2Array, $cas1Array, $cas2Array) {
    $points = PointsComputor::teamWin2TeamDraw1TeamLoss0($td1Array, $td2Array, $cas1Array, $cas2Array);
    $points1 = $points['points1'] * self::POINT_MULTIPLIER;
    $points2 = $points['points2'] * self::POINT_MULTIPLIER;
  }

  public function useOpponentPointsOfYourOwnMatch() {
    return false;
  }

  public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','win','draw','opponentsPoints')
    ),
    'coachTeam' => array(
      'main' => array('coachTeamPoints','win','draw','opponentCoachTeamPoints','opponentPoints'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking'),
      'td' => array('tdFor'),
      'fouls' => array('foulsFor'),
      'casualties' => array('casualtiesFor'),
      'defense' => array('tdAgainst')
      ),
    'special' => array(
      'guest' => 'usage'
      )
    );
  }
}