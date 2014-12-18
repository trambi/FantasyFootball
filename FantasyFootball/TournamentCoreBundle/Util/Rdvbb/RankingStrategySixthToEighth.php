<?php
namespace FantasyFootball\TournamentCoreBundle\Util\Rdvbb;

use FantasyFootball\TournamentCoreBundle\Util\IRankingStrategy;

class RankingStrategySixthToEighth implements IRankingStrategy{
  const VAINQUEUR = 2;
  const FINALISTE = 1;
  const NORMAL = 0;

	public function useCoachTeamPoints(){
		return false;
	}  
  
  public function computePoints(&$points1,&$points,$td1,$td2,$cas1,$cas2){
    if($td1==$td2){
     $points1 = 3;
     $points2 = 3;
    }else if($td1==$td2+1){
     $points1 = 5;
     $points2 = 1;
    }else if($td1>$td2+1){
     $points1 = 5;
     $points2 = 0;
    }else if($td2==$td1+1){
     $points1 = 1;
     $points2 = 5;
    }else if($td2>$td1+1){
     $points1 = 0;
     $points2 = 5;
    }else if($td1 ==-1){
      $points1 = -1;
      $points2 = 5;
      $td2 = 2;
    }else if($td2 ==-1){
      $points2 = -1;
      $points1 = 5;
      $td1 = 2;
    }
  }

  	public function compareCoachs($team1,$team2){
		$retour =1;
		if(isset($team1->special)){
			$special1 = $team1->special;
		}else{
			$special1 = 0;
		}
		if(isset($team2->special)){
			$special2 = $team2->special;
		}else{
			$special2 = 0;
		}
		if(self::VAINQUEUR == $special1){
			return -1;
		}else if(self::VAINQUEUR == $special2){
			return 1;
		}else if(self::FINALISTE == $special1){
			return -1;
		}else if(self::FINALISTE == $special2){
			return 1;
		}
    
		$points1 = $team1->points;
		$points2 = $team2->points;
		$opponentPoints1 = $team1->opponentsPoints;
		$opponentPoints2 = $team2->opponentsPoints;
		$netTd1 = $team1->netTd;
		$netTd2 = $team2->netTd;
		$cas1 = $team1->casualties;
		$cas2 = $team2->casualties;
		
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
  		return $this->compareCoachs($coachTeam1,$coachTeam2);
  	}
  	
  public function computeCoachTeamPoints(&$points1,&$points2,$td1Array,$td2Array,$cas1Array,$cas2Array){
  		$points1 = 0;
		$points2 = 0;
		for( $i = 0 ; $i < 3 ; $i++){
			$tempPoints1 = 0;
			$tempPoints2 = 0;
  			$this->computePoints(&$tempPoints1,&$tempPoints2,$td1Array[$i],$td2Array[$i],$cas1Array[$i],$cas2Array[$i]);
  			$points1 += $tempPoints1;
			$points2 += $tempPoints2;
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
  public function getFirstFreeTeamIndex(&$avEquipes, $start){
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
  public function useOpponentPointsOfYourOwnMatch(){
  	return false;	
  }
}

?>
