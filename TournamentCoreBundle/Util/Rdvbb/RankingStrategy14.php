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
    return TeamComparator::pointsWinDrawOpponentsPointsValue($coach1,$coach2);
  }

  public function compareCoachTeams($coachTeam1, $coachTeam2) {
    return TeamComparator::finalPointsWinDrawOpponentsPointsValue($coachTeam1, $coachTeam2);
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
      'main' => array('points','win','draw','opponentPoints','value'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking'),
      'td' => array('tdFor'),
      'foul' => array('foulsFor'),
      'pass' => array('completionsFor'),
      'casualties' => array('casualtiesFor'),
    ),
    'coachTeam' => array(
      'main' => array('points','win','draw','opponentPoints','value'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking'),
      'td' => array('td'),
      'foul' => array('foulsFor'),
      'pass' => array('completionsFor'),
      'casualties' => array('casualtiesFor')
      ),
    'special' => array(
      'guest' => 'usage'
      )
    );
  }
}