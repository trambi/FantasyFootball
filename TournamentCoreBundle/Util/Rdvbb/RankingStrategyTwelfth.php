<?php

namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyTwelfth implements IRankingStrategy {

  public function useCoachTeamPoints() {
    return true;
  }

  public function computePoints(&$points1, &$points2, $td1, $td2, $cas1, $cas2) {
    $points = PointsComputor::win8Draw4Loss0Bonus1ConcedeMinus1($td1,$td2,$cas1,$cas2);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
  }

  public function compareCoachs($coach1, $coach2) {
    return TeamComparator::pointsOpponentPointsNetTdCasFor($coach1, $coach2);
  }

  public function compareCoachTeams($coachTeam1, $coachTeam2) {
    return TeamComparator::coachTeamPointsPointsOpponentPointsNetTdCasFor($coachTeam1, $coachTeam2);
  }

  public function computeCoachTeamPoints(&$points1, &$points2, $td1Array, $td2Array, $cas1Array, $cas2Array) {
    $points = PointsComputor::teamWin2TeamDraw1TeamLoss0win8Draw4Loss0Bonus1ConcedeMinus1($td1Array, $td2Array, $cas1Array, $cas2Array);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
    
  }

  public function useOpponentPointsOfYourOwnMatch() {
    return false;
  }
}
?>