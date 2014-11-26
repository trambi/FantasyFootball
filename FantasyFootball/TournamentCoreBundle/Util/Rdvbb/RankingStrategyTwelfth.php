<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategyTwelfth implements IRankingStrategy{
	
	public function useCoachTeamPoints(){
		return true;
	}
		
	public function computePoints(&$points1,&$points2,$td1,$td2,$cas1,$cas2){
   	if($td1 == $td2){
     		$points1 = 4;
     		$points2 = 4;
    	}else if( $td1 == ($td2+1) ){
     		$points1 = 8;
     		$points2 = 1;
    	}else if($td1 > ($td2+1) ){
     		$points1 = 9;
     		$points2 = 0;
    	}else if( $td2 == ($td1+1) ){
     		$points1 = 1;
     		$points2 = 8;
    	}else if($td2 > ($td1+1) ){
     		$points1 = 0;
     		$points2 = 9;
    	}else if(-1 == $td1 ){
      	$points1 = -1;
      	$points2 = 9;
      	$td2 = 2;
    	}else if(-1 == $td2 ){
      	$points2 = -1;
      	$points1 = 9;
      	$td1 = 2;
    	}
  	}
	
	public function compareCoachs($coach1,$coach2){
		$retour =1;
    
		$points1 = $coach1->points;
		$points2 = $coach2->points;
		$opponentPoints1 = $coach1->opponentsPoints;
		$opponentPoints2 = $coach2->opponentsPoints;
		$netTd1 = $coach1->netTd;
		$netTd2 = $coach2->netTd;
		$cas1 = $coach1->casualties;
		$cas2 = $coach2->casualties;
		
		if(($points1 == $points2) 
			&& ($opponentPoints1 == $opponentPoints2) 
			&& ($netTd1 == $netTd2) 
			&& ($cas1 == $cas2) ){
		  $retour = 0;
		}else{
		  if($points1 > $points2){
			$retour = 1;
		  }
		  if($points1 < $points2){
			$retour = -1;
		  }
		  if($points1 == $points2){
			if($opponentPoints1 > $opponentPoints2){
			  $retour = 1;
			}
			if($opponentPoints1 < $opponentPoints2){
			  $retour = -1;
			}
			if($opponentPoints1 == $opponentPoints2){
			  if($netTd1 > $netTd2){
				$retour = 1;
			  }
			  if($netTd1 < $netTd2){
				$retour = -1;
			  }
			  if($netTd1 == $netTd2){
				if($cas1 > $cas2){
				  $retour = 1;
				}
				if($cas1 < $cas2){
				  $retour = -1;
				}
			  }
			}
		  }
		}
		return -$retour;
	}

  public function compareCoachTeams($coachTeam1,$coachTeam2){
  		$points1 = $triplette1->coachTeamPoints;
    	$points2 = $triplette2->coachTeamPoints;
    	if( $points1 > $points2 ){
			return -1;
		}
    	elseif( $points1 < $points2 ) {
    		return 1;
		}
		else{
  			return $this->compareCoachs($coachTeam1,$coachTeam2);
  		}
  	}
  	
  public function computeCoachTeamPoints(&$points1,&$points2,$td1Array,$td2Array,$cas1Array,$cas2Array){
  		$points1 = 0;
		$points2 = 0;
		$sum1 = 0;
		$sum2 = 0;
		for( $i = 0 ; $i < 3 ; $i++){
			$tempPoints1 = 0;
			$tempPoints2 = 0;
  			$this->computePoints(&$tempPoints1,&$tempPoints2,$td1Array[$i],$td2Array[$i],$cas1Array[$i],$cas2Array[$i]);
  			$sum1 += $tempPoints1;
			$sum2 += $tempPoints2;
		}
		if( $sum1 < $sum2){
			$points1 = 0;
			$points2 = 2;
		}elseif( $sum1 === $sum2 ) {
			$points1 = 1;
			$points2 = 1;
		}else {
			$points1 = 2;
			$points2 = 0;
		}
  	}
  
  public function teamsCanPlay($team1,$team2){
    $opponentCount = 0;
    $temp = "adv_0";
    $id1 =  $team1['id'];
    $id2 =  $team2['id'];
    $return = 1;
    while(isset($team1[$temp])){
      if($team1[$temp] == $id2){
        $return = 0;
        break;
      }
      $opponentCount++;
      $temp = "adv_$opponentCount";
    }
    if(isset($_GET['debug'])and $_GET['debug']==1){
      echo '* jouable : equipe 1 ',$id,'(',$team1['coach'],')','equipe 2 ',$id2,'(',$team2['coach'],')','retour ',$return,"<br />\n";
    }
    return $return;
  }

  // retourne l'indice de la premiere equipe libre sur le tableau du classement //
  function getFirstFreeTeamIndex(&$avEquipes, $start){
    $count = $start;
    $teamNumber = count($avEquipes);
    if(isset($_GET['debug'])and $_GET['debug']==1){
      echo "! rang_libre : equipe libre ".$avEquipes[$count]['libre']."<br />\n";
    }
    while( ($avEquipes[$count]['libre']==0) && ($count <$teamNumber)){
      $count ++;
    }
    if($count < $teamNumber){
      $avEquipes[$count]['libre'] = 0;
    }
    if(isset($_GET['debug'])and $_GET['debug']==1){
      echo "! rang_libre rang = $start, return $count<br />\n";
    }
    return $count;
  }
  		public function compareTeamObjectsByTouchdown($team1,$team2){
			$retour =1;
			$td1 = $team1->td;
			$td2 = $team2->td;

			if($td1 > $td2){
				$retour = -1;
			}else if($td1 < $td2){
				$retour = 1;
			}else{
				$retour = 0;
			}
			return $retour;
		}
		
		public function compareTeamObjectsByCasualties($team1,$team2){
			$retour =1;
			$cas1 = $team1->casualties;
			$cas2 = $team2->casualties;

			if($cas1 > $cas2){
				$retour = -1;
			}else if($cas1 < $cas2){
				$retour = 1;
			}else{
				$retour = 0;
			}
			return $retour;
		}
		
	public function useOpponentPointsOfYourOwnMatch(){
  		return false;	
	}
}

?>
