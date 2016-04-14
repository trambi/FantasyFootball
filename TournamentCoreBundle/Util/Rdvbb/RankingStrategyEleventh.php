<?php

namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyEleventh implements IRankingStrategy{
  const VAINQUEUR = 2;
  const FINALISTE = 1;
  const NORMAL = 0;

  public function useCoachTeamPoints(){
    return false;
  }

  public function computePoints(&$points1,&$points2,$td1,$td2,$cas1,$cas2){
    $points = PointsComputor::win8Draw4Loss0Bonus1($td1,$td2,$cas1,$cas2);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
  }

  public function compareCoachs($team1,$team2){
    return TeamComparator::finalPointsOpponentsPointsNetTdCasFor($team1,$team2);
  }


  public function compareCoachTeams($coachTeam1,$coachTeam2){
    return TeamComparator::pointsOpponentsPointsNetTdCasFor($coachTeam1,$coachTeam2);
  }

  public function computeCoachTeamPoints(&$points1,&$points2,$td1Array,$td2Array,$cas1Array,$cas2Array){
    $points = PointsComputor::teamWithWin8Draw4Loss0Bonus1($td1Array,$td2Array,$cas1Array,$cas2Array);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
  }

  public function useOpponentPointsOfYourOwnMatch(){
    return false;
  }
  
    public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','opponentsPoints','netTd','casualties'),
      'td' => array('td'),
      'casualties' => array('casualties'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking')
    ),
    'coachTeam' => array(
      'main' => array('points','opponentsPoints','netTd','casualties'),
      )
    );
  }
  

}

?>