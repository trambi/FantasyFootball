<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyNinthToTenth implements IRankingStrategy{
  
  public function useCoachTeamPoints(){
    return false;
  }  
  
  public function computePoints(&$points1,&$points2,$td1,$td2,$cas1,$cas2){
    $points = PointsComputor::win10Draw4SmallLoss1Loss0($td1,$td2,$cas1,$cas2);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
  }
    
  public function compareCoachs($team1,$team2){
    return TeamComparator::finalPointsOpponentPointsNetTdCasFor($team1,$team2);
  }

  
  public function compareCoachTeams($coachTeam1,$coachTeam2){
    return $this->compareCoachs($coachTeam1,$coachTeam2);
  }
    
  public function computeCoachTeamPoints(&$points1,&$points2,$td1Array,$td2Array,$cas1Array,$cas2Array){
    $points = PointsComputor::teamWithWin10Draw4SmallLoss1Loss0($td1Array,$td2Array,$cas1Array,$cas2Array);
    $points1 = $points['points1'];
    $points2 = $points['points2'];
    }
            
    public function useOpponentPointsOfYourOwnMatch(){
        return false;   
    }
}

?>
