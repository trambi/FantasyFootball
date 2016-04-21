<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategy6To8 implements IRankingStrategy{

  public function useCoachTeamPoints(){
    return false;
  }  
  
  public function computePoints(&$points1,&$points2,$td1,$td2,$cas1,$cas2){
    $points = PointsComputor::win5Draw3SmallLoss1Loss0($td1,$td2,$cas1,$cas2);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
  }

  public function compareCoachs($team1,$team2){
    return TeamComparator::finalPointsOpponentsPointsNetTdCasFor($team1,$team2);
  }
  
  public function compareCoachTeams($coachTeam1,$coachTeam2){
    return TeamComparator::finalPointsOpponentsPointsNetTdCasFor($coachTeam1,$coachTeam2);
  }
    
  public function computeCoachTeamPoints(&$points1,&$points2,$td1Array,$td2Array,$cas1Array,$cas2Array){
    $points1 = 0;
    $points2 = 0;
    for( $i = 0 ; $i < 3 ; $i++){
      $tempPoints1 = 0;
      $tempPoints2 = 0;
      $points = PointsComputor::win5Draw3SmallLoss1Loss0($td1Array[$i],$td2Array[$i],$cas1Array[$i],$cas2Array[$i]);
      $points1 += $points['points1'];
      $points2 += $points['points2'];
    }
  }  

  public function useOpponentPointsOfYourOwnMatch(){
    return false;   
  }
  
  public function rankingOptions(){
    return array(
    'coach' => array(
      'main' => array('points','opponentPoints','netTd','casualtiesFor'),
      'td' => array('tdFor'),
      'casualties' => array('casualtiesFor'),
      'comeback' => array('diffRanking','firstDayRanking','finalRanking')
    ),
    'coachTeam' => array(
      'main' => array('points','opponentPoints','netTd','casualtiesFor'),
      )
    );
  }
}

?>