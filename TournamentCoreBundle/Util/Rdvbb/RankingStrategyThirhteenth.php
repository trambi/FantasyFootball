<?php

namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyThirhteenth implements IRankingStrategy {
  const TEAM_DRAW = 50;
  const TEAM_WIN = 150;
  const TEAM_LOSS = 0;

  public function useCoachTeamPoints() {
    return true;
  }

  public function computePoints(&$points1, &$points2, $td1, $td2, $cas1, $cas2) {
    $points = PointsComputor::win300Draw100Loss0($td1,$td2,$cas1,$cas2);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
  }
    
  public function compareCoachs($coach1, $coach2) {
    return TeamComparator::pointsOpponentsPointsTdForPlusCasFor($coach1,$coach2);
  }

  public function compareCoachTeams($coachTeam1, $coachTeam2) {
    return TeamComparator::coachTeamPointsPointsOpponentsPointsTdForPlusCasFor($coachTeam1, $coachTeam2);
  }

  public function computeCoachTeamPoints(&$points1, &$points2, $td1Array, $td2Array, $cas1Array, $cas2Array) {
    $points = PointsComputor::teamWin300Draw100Loss0HalfTeamBonus($td1Array, $td2Array, $cas1Array, $cas2Array);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
  }

  public function useOpponentPointsOfYourOwnMatch() {
    return true;
  }
  
  public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','opponentsPoints','netTd','casualtiesFor'),
      'td' => array('tdFor'),
      'casualties' => array('casualtiesFor'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking')
    ),
    'coachTeam' => array(
      'main' => array('points','opponentsPoints','netTd','casualtiesFor'),
      )
    );
  }
}