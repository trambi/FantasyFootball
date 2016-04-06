<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyFourthToFifth implements IRankingStrategy{

  public function useCoachTeamPoints(){
    return false;
  }

  public function computePoints(&$points1,&$points2,$td1,$td2,$cas1,$cas2){
    if ( $td1 === $td2 ) {
      $points1 = 3;
      $points2 = 3;
    }else if ( $td1 === $td2+1 ) {
      $points1 = 5;
      $points2 = 1;
    }else if ( $td1 > $td2+1 ) {
      $points1 = 5;
      $points2 = 0;
    }else if( $td2 === $td1+1){
      $points1 = 1;
      $points2 = 5;
    }else if( $td2 > $td1+1 ){
      $points1 = 0;
      $points2 = 5;
    }else if( -1 === $td1 ){
      $points1 = -1;
      $points2 = 5;
      $td2 = 2;
    }else if( -1 === $td2 ){
      $points2 = -1;
      $points1 = 5;
      $td1 = 2;
    }
  }
  
  public function compareCoachs($team1,$team2){
    $retour =1;
    
	$points1 = $team1->points;
	$points2 = $team2->points;
	$opponentPoints1 = $team1->opponentsPoints;
	$opponentPoints2 = $team2->opponentsPoints;
	$netTd1 = $team1->netTd;
	$netTd2 = $team2->netTd;
	$cas1 = $team1->casualties;
	$cas2 = $team2->casualties;
		
	if( ($points1 === $points2) 
	&& ($opponentPoints1 == $opponentPoints2) 
	&& ($netTd1 == $netTd2) 
	&& ($cas1 == $cas2) ){
	  $retour = 0;
	}else if($points1 > $points2){
		$retour = 1;
    }else if($points1 < $points2){
			$retour = -1;
	}else if($points1 === $points2){
	  if ($opponentPoints1 > $opponentPoints2) {
	    $retour = 1;
	  }else if ($opponentPoints1 < $opponentPoints2) {
        $retour = -1;
	  }else if ($opponentPoints1 === $opponentPoints2) {
		if($netTd1 > $netTd2){
          $retour = 1;
        }else if($netTd1 < $netTd2){
          $retour = -1;
        }else if($netTd1 === $netTd2){
          if($cas1 > $cas2){
            $retour = 1;
          }else if($cas1 < $cas2){
            $retour = -1;
          }
        }
      }
    }
    return -$retour;
  }
	
	public function compareCoachTeams($coachTeam1,$coachTeam2){
  		return $this->compareCoachs($coachTeam1,$coachTeam2);
  	}
  	
  public function computeCoachTeamPoints(&$points1,&$points2,$td1Array,$td2Array,$cas1Array,$cas2Array){
  		$points1 = 0;
		$points2 = 0;
		foreach( $td1Array as $i => $value ){
			$tempPoints1 = 0;
			$tempPoints2 = 0;
  			$this->computePoints($tempPoints1,$tempPoints2,$td1Array[$i],$td2Array[$i],$cas1Array[$i],$cas2Array[$i]);
  			$points1 += $tempPoints1;
			$points2 += $tempPoints2;
		}
  	}

  public function useOpponentPointsOfYourOwnMatch(){
  	return false;	
  }
  
  public function rankingOptions(){
    return array(
      'coach' => array(
        'main' => array('points','opponentPoints','netTd','casualties'),
        'td' => array('td'),
        'casualties' => array('casualties'),
        'comeback' => array('diffRanking','firstDayRanking','finalRanking')
      ),
      'coachTeam' => array(
        'main' => array('points','opponentPoints','netTd','casualties'),
      )
    );
  }
}

?>